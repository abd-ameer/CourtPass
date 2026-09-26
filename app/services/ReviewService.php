<?php
class ReviewService
{
    public const WINDOW_DAYS = 7;
    public const COMMENT_MAX = 1000;
    public const REASON_MAX = 500;
    public const MODERATION_FILTERS = ['flagged', 'active', 'removed'];

    private ReviewModel $reviews;

    public function __construct()
    {
        $this->reviews = new ReviewModel();
    }

    /**
     * The customer's booking with what the review form needs: checked_in_at, review_until, review_id
     * (their existing review of it), can_review and, when it cannot be reviewed, reason. Null when the booking is not theirs.
     */
    public function reviewableBooking(int $customerId, int $bookingId): ?array
    {
        $booking = (new BookingService())->customerBooking($customerId, $bookingId);
        if ($booking === null) {
            return null;
        }
        $checkedInAt = (new CheckInModel())->forBookings([$booking['id']])[$booking['id']] ?? null;
        $existing = null;
        foreach ($this->reviews->forReviewer($customerId) as $review) {
            if ((int) $review['booking_id'] === $booking['id']) {
                $existing = $review;
            }
        }

        $booking['checked_in_at'] = $checkedInAt;
        $booking['review_until'] = $checkedInAt === null ? null : self::windowEnd($checkedInAt);
        $booking['review_id'] = $existing === null ? null : (int) $existing['id'];
        $booking['review_status'] = $existing['status'] ?? null;
        $booking['reason'] = match (true) {
            $existing !== null => "You have already reviewed booking #{$booking['id']}.",
            $checkedInAt === null => "You can review booking #{$booking['id']} once the venue has checked you in.",
            now() >= $booking['review_until'] => "The 7-day review window for booking #{$booking['id']} closed on " . format_datetime($booking['review_until']) . '.',
            default => null,
        };
        $booking['can_review'] = $booking['reason'] === null;
        return $booking;
    }

    /** Ids of the customer's checked-in bookings that can still be reviewed. */
    public function reviewableBookingIds(int $customerId): array
    {
        return array_column($this->toReview($customerId), 'id');
    }

    /**
     * Reviews a checked-in booking of the customer, within 7 days of the check-in, once per booking.
     * Returns the new review id and notifies the venue owner.
     */
    public function create(int $customerId, int $bookingId, mixed $rating, mixed $comment): int
    {
        [$rating, $comment] = self::validContent($rating, $comment);
        $booking = $this->reviewableBooking($customerId, $bookingId);
        if ($booking === null) {
            throw new ValidationException(['booking' => 'Booking not found.']);
        }
        if (!$booking['can_review']) {
            throw new ValidationException(['review' => $booking['reason']]);
        }

        try {
            return Database::transaction(function () use ($customerId, $booking, $rating, $comment): int {
                $id = $this->reviews->create($customerId, $booking['venue_id'], $booking['id'], $rating, $comment, now());
                (new AuditService())->log($customerId, 'review.created', 'review', $id, null, 'active',
                    json_encode(['booking_id' => $booking['id'], 'venue_id' => $booking['venue_id'], 'rating' => $rating]));
                (new NotificationService())->notify(
                    $booking['owner_id'],
                    'review_posted',
                    'New review',
                    "{$booking['venue_name']} received a {$rating}-star review from {$booking['customer_name']} for {$booking['court_name']} on "
                        . format_datetime($booking['starts_at']) . '.',
                    '/owner/reviews'
                );
                return $id;
            });
        } catch (mysqli_sql_exception $e) {
            // 1062 = a review of this booking was saved first
            if ($e->getCode() === 1062) {
                throw new ValidationException(['review' => "You have already reviewed booking #{$booking['id']}."]);
            }
            throw $e;
        }
    }

    /** Changes the rating and comment of the customer's own active review, inside the 7-day window. */
    public function update(int $customerId, int $reviewId, mixed $rating, mixed $comment): void
    {
        [$rating, $comment] = self::validContent($rating, $comment);
        Database::transaction(function () use ($customerId, $reviewId, $rating, $comment): void {
            $row = $this->lockOwnReview($customerId, $reviewId);
            if ($row['status'] !== 'active') {
                throw new ValidationException(['review' => 'This review was removed by the Platform Admin, so it can no longer be edited.']);
            }
            $checkedInAt = (new CheckInModel())->forBookings([(int) $row['booking_id']])[(int) $row['booking_id']];
            $until = self::windowEnd($checkedInAt);
            if (now() >= $until) {
                throw new ValidationException(['review' => 'The 7-day window for editing this review closed on ' . format_datetime($until) . '.']);
            }
            if ($this->reviews->updateContent($reviewId, $rating, $comment) !== 1) {
                throw new ValidationException(['review' => 'This review can no longer be edited.']);
            }
            (new AuditService())->log($customerId, 'review.updated', 'review', $reviewId, 'active', 'active', json_encode([
                'old' => ['rating' => (int) $row['rating'], 'comment' => $row['comment']],
                'new' => ['rating' => $rating, 'comment' => $comment],
            ]));
        });
    }

    /**
     * Deletes the customer's own active review. The audit entry keeps its content, and the booking
     * can be reviewed again while its window is open. A removed review stays for moderation.
     */
    public function delete(int $customerId, int $reviewId): void
    {
        Database::transaction(function () use ($customerId, $reviewId): void {
            $row = $this->lockOwnReview($customerId, $reviewId);
            if ($row['status'] !== 'active') {
                throw new ValidationException(['review' => 'This review was removed by the Platform Admin, so it cannot be deleted.']);
            }
            $flags = (new ReviewFlagModel())->deleteForReview($reviewId);
            if ($this->reviews->delete($reviewId) !== 1) {
                throw new ValidationException(['review' => 'This review can no longer be deleted.']);
            }
            (new AuditService())->log($customerId, 'review.deleted', 'review', $reviewId, 'active', null, json_encode([
                'booking_id' => (int) $row['booking_id'],
                'venue_id'   => (int) $row['venue_id'],
                'rating'     => (int) $row['rating'],
                'comment'    => $row['comment'],
                'response'   => $row['response_text'],
                'flags'      => $flags,
            ]));
        });
    }

    /** Admin hides a venue review with a reason; an open flag on it is closed as upheld and the reviewer is told. */
    public function remove(int $adminId, int $reviewId, mixed $reason): void
    {
        $reason = self::validReason($reason, 'reason');
        Database::transaction(function () use ($adminId, $reviewId, $reason): void {
            $row = $this->reviews->lockForUpdate($reviewId);
            if ($row === null || $row['venue_id'] === null) {
                throw new ValidationException(['review' => 'Review not found.']);
            }
            if ($this->reviews->remove($reviewId, $adminId, $reason) !== 1) {
                throw new ValidationException(['review' => 'This review has already been removed.']);
            }
            $upheld = (new ReviewFlagModel())->resolveOpen($reviewId, 'upheld', $adminId, now()) === 1;
            (new AuditService())->log($adminId, 'review.removed', 'review', $reviewId, 'active', 'removed',
                json_encode(['reason' => $reason, 'flag_upheld' => $upheld]));
            $review = $this->reviews->find($reviewId);
            (new NotificationService())->notify(
                (int) $review['reviewer_id'],
                'review_removed',
                'Review removed',
                "Your review of {$review['venue_name']} was removed by the Platform Admin. Reason: {$reason}",
                '/customer/reviews'
            );
        });
    }

    /** The venue owner's single public response to an active review at their venue. */
    public function respond(int $ownerId, int $reviewId, mixed $response): void
    {
        $response = trim((string) $response);
        $length = mb_strlen($response);
        if ($length < 1 || $length > self::COMMENT_MAX) {
            throw new ValidationException(['response' => 'Write a response of 1 to ' . self::COMMENT_MAX . ' characters.']);
        }
        $review = $this->ownerReview($ownerId, $reviewId);
        Database::transaction(function () use ($ownerId, $reviewId, $response, $review): void {
            $row = $this->reviews->lockForUpdate($reviewId);
            if ($row === null) {
                throw new ValidationException(['review' => 'Review not found.']);
            }
            if ($row['status'] !== 'active') {
                throw new ValidationException(['review' => 'This review was removed by the Platform Admin.']);
            }
            if ($row['response_text'] !== null || $this->reviews->respond($reviewId, $ownerId, $response, now()) !== 1) {
                throw new ValidationException(['review' => 'You have already responded to this review.']);
            }
            (new AuditService())->log($ownerId, 'review.responded', 'review', $reviewId, null, null, json_encode(['response' => $response]));
            (new NotificationService())->notify(
                (int) $review['reviewer_id'],
                'review_response',
                'Owner responded to your review',
                "{$review['venue_name']} responded to your review.",
                '/customer/reviews'
            );
        });
    }

    /** The owner reports an active review at their venue to the Platform Admin. One open flag per review. */
    public function flag(int $ownerId, int $reviewId, mixed $reason): void
    {
        $reason = self::validReason($reason, 'reason');
        $this->ownerReview($ownerId, $reviewId);
        try {
            Database::transaction(function () use ($ownerId, $reviewId, $reason): void {
                $row = $this->reviews->lockForUpdate($reviewId);
                if ($row === null) {
                    throw new ValidationException(['review' => 'Review not found.']);
                }
                if ($row['status'] !== 'active') {
                    throw new ValidationException(['review' => 'This review was already removed by the Platform Admin.']);
                }
                $flagId = (new ReviewFlagModel())->create($reviewId, $ownerId, $reason);
                (new AuditService())->log($ownerId, 'review.flagged', 'review', $reviewId, null, null,
                    json_encode(['flag_id' => $flagId, 'reason' => $reason]));
            });
        } catch (mysqli_sql_exception $e) {
            // 1062 = the review already has an open flag
            if ($e->getCode() === 1062) {
                throw new ValidationException(['review' => 'This review is already reported and waiting for the Platform Admin.']);
            }
            throw $e;
        }
    }

    /** Admin keeps a flagged review; the flag is closed as dismissed and the owner who raised it is told. */
    public function dismissFlag(int $adminId, int $reviewId): void
    {
        Database::transaction(function () use ($adminId, $reviewId): void {
            $row = $this->reviews->lockForUpdate($reviewId);
            if ($row === null || $row['venue_id'] === null) {
                throw new ValidationException(['review' => 'Review not found.']);
            }
            $review = $this->reviews->find($reviewId);
            if ($review['flag_id'] === null || (new ReviewFlagModel())->resolveOpen($reviewId, 'dismissed', $adminId, now()) !== 1) {
                throw new ValidationException(['review' => 'This review has no open report.']);
            }
            (new AuditService())->log($adminId, 'review.flag_dismissed', 'review', $reviewId, null, null,
                json_encode(['flag_id' => (int) $review['flag_id']]));
            (new NotificationService())->notify(
                (int) $review['flagged_by'],
                'review_flag_dismissed',
                'Review report closed',
                "The Platform Admin checked your report on {$review['reviewer_name']}'s review of {$review['venue_name']} and kept the review.",
                '/owner/reviews'
            );
        });
    }

    /** One of the customer's own venue reviews, for the edit form, or null. */
    public function customerReview(int $customerId, int $reviewId): ?array
    {
        $review = $this->reviews->find($reviewId);
        if ($review === null || (int) $review['reviewer_id'] !== $customerId || $review['venue_id'] === null) {
            return null;
        }
        return self::present($review);
    }

    /** ['reviews' => the customer's venue and coach reviews, 'to_review' => checked-in bookings still waiting for a review]. */
    public function customerReviews(int $customerId): array
    {
        return [
            'reviews'   => array_map(fn (array $r) => self::present($r), $this->reviews->forReviewer($customerId)),
            'to_review' => $this->toReview($customerId),
        ];
    }

    /** Active reviews of a venue, newest first. */
    public function venueReviews(int $venueId): array
    {
        return array_map(fn (array $r) => self::present($r), $this->reviews->activeForVenue($venueId));
    }

    /** [venue_id => ['avg_rating' => ?float, 'review_count' => int]] from active reviews only. */
    public function venueRatings(array $venueIds): array
    {
        $ratings = $this->reviews->ratingsFor($venueIds);
        $out = [];
        foreach ($venueIds as $id) {
            $out[(int) $id] = $ratings[(int) $id] ?? ['avg_rating' => null, 'review_count' => 0];
        }
        return $out;
    }

    /** ['reviews', 'venues' (with ratings), 'venue_id'] for the owner's reviews page. */
    public function ownerReviews(int $ownerId, ?int $venueId = null): array
    {
        $venues = array_map(fn (array $v) => ['id' => $v['id'], 'name' => $v['name']], (new VenueService())->ownerVenues($ownerId));
        $ratings = $this->venueRatings(array_column($venues, 'id'));
        foreach ($venues as $i => $venue) {
            $venues[$i] += $ratings[$venue['id']];
        }
        if ($venueId !== null && !in_array($venueId, array_column($venues, 'id'), true)) {
            $venueId = null;
        }
        return [
            'reviews'  => array_map(fn (array $r) => self::present($r), $this->reviews->forOwner($ownerId, $venueId)),
            'venues'   => $venues,
            'venue_id' => $venueId,
        ];
    }

    /** ['reviews', 'status', 'counts'] for the admin moderation page. */
    public function moderation(string $status): array
    {
        $counts = [];
        foreach (self::MODERATION_FILTERS as $filter) {
            $counts[$filter] = count($this->reviews->forModeration($filter));
        }
        if (!in_array($status, self::MODERATION_FILTERS, true)) {
            $status = $counts['flagged'] > 0 ? 'flagged' : 'active';
        }
        return [
            'reviews' => array_map(fn (array $r) => self::present($r), $this->reviews->forModeration($status)),
            'status'  => $status,
            'counts'  => $counts,
        ];
    }

    /** Checked-in bookings of the customer inside the review window that have no review yet. */
    private function toReview(int $customerId): array
    {
        $completed = array_values(array_filter(
            (new BookingService())->customerBookings($customerId),
            fn (array $b) => $b['status'] === 'completed'
        ));
        $checkIns = (new CheckInModel())->forBookings(array_column($completed, 'id'));
        $reviewed = array_map('intval', array_column($this->reviews->forReviewer($customerId), 'booking_id'));
        $now = now();
        $out = [];
        foreach ($completed as $b) {
            $at = $checkIns[$b['id']] ?? null;
            if ($at === null || in_array($b['id'], $reviewed, true) || $now >= self::windowEnd($at)) {
                continue;
            }
            $b['checked_in_at'] = $at;
            $b['review_until'] = self::windowEnd($at);
            $out[] = $b;
        }
        return $out;
    }

    /** The review at one of the owner's venues, or a not-found error. */
    private function ownerReview(int $ownerId, int $reviewId): array
    {
        $review = $this->reviews->find($reviewId);
        if ($review === null || $review['venue_id'] === null || (int) $review['owner_id'] !== $ownerId) {
            throw new ValidationException(['review' => 'Review not found.']);
        }
        return $review;
    }

    /** Locks the customer's own venue review, or a not-found error. */
    private function lockOwnReview(int $customerId, int $reviewId): array
    {
        $row = $this->reviews->lockForUpdate($reviewId);
        if ($row === null || (int) $row['reviewer_id'] !== $customerId || $row['venue_id'] === null) {
            throw new ValidationException(['review' => 'Review not found.']);
        }
        return $row;
    }

    private static function windowEnd(string $checkedInAt): string
    {
        return date('Y-m-d H:i:s', strtotime($checkedInAt . ' +' . self::WINDOW_DAYS . ' days'));
    }

    /** [rating, comment]: rating a whole number 1 to 5, comment trimmed, 1 to COMMENT_MAX characters. */
    private static function validContent(mixed $rating, mixed $comment): array
    {
        $errors = [];
        $rating = filter_var($rating, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 5]]);
        if ($rating === false) {
            $errors['rating'] = 'Choose a rating from 1 to 5 stars.';
        }
        $comment = trim((string) $comment);
        $length = mb_strlen($comment);
        if ($length < 1 || $length > self::COMMENT_MAX) {
            $errors['comment'] = $length < 1 ? 'Write a comment about your visit.' : 'Keep the comment to ' . self::COMMENT_MAX . ' characters or fewer.';
        }
        if ($errors !== []) {
            throw new ValidationException($errors);
        }
        return [$rating, $comment];
    }

    private static function validReason(mixed $reason, string $field): string
    {
        $reason = trim((string) $reason);
        $length = mb_strlen($reason);
        if ($length < 1 || $length > self::REASON_MAX) {
            throw new ValidationException([$field => 'Give a reason of 1 to ' . self::REASON_MAX . ' characters.']);
        }
        return $reason;
    }

    /** Casts ids and adds the flags the views use. */
    private static function present(array $r): array
    {
        foreach (['id', 'reviewer_id', 'rating'] as $key) {
            $r[$key] = (int) $r[$key];
        }
        foreach (['venue_id', 'booking_id', 'coach_id', 'registration_id', 'owner_id', 'flag_id', 'flagged_by'] as $key) {
            $r[$key] = $r[$key] === null ? null : (int) $r[$key];
        }
        $active = $r['status'] === 'active';
        $r['is_venue'] = $r['venue_id'] !== null;
        $r['review_until'] = $r['is_venue'] && $r['checked_in_at'] !== null ? self::windowEnd($r['checked_in_at']) : null;
        $r['in_window'] = $r['review_until'] !== null && now() < $r['review_until'];
        $r['can_edit'] = $r['is_venue'] && $active && $r['in_window'];
        $r['can_delete'] = $r['is_venue'] && $active;
        $r['can_respond'] = $r['is_venue'] && $active && $r['response_text'] === null;
        $r['can_flag'] = $r['is_venue'] && $active && $r['flag_id'] === null;
        $r['visit'] = $r['slot_date'] === null ? null : $r['slot_date'] . ' ' . $r['start_time'];
        return $r;
    }
}
