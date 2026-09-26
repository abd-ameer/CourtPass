<?php
class ReviewController extends Controller
{
    public function index(): void
    {
        $this->view('customer/reviews', ['title' => 'My Reviews'] + (new ReviewService())->customerReviews(Auth::id()), 'dashboard');
    }

    public function create(string $id): void
    {
        $booking = $this->reviewableBooking((int) $id);
        $this->reviewForm('Review Venue', $booking, null, ['rating' => 5, 'comment' => ''], []);
    }

    public function store(string $id): void
    {
        $this->verifyCsrf();
        $booking = $this->reviewableBooking((int) $id);
        $input = $this->reviewInput();
        try {
            (new ReviewService())->create(Auth::id(), $booking['id'], $input['rating'], $input['comment']);
            Session::flash('success', "Your review of {$booking['venue_name']} is published.");
            $this->redirect('/customer/reviews');
        } catch (ValidationException $e) {
            if (isset($e->errors()['review']) || isset($e->errors()['booking'])) {
                Session::flash('error', $e->getMessage());
                $this->redirect('/customer/reviews');
            }
            http_response_code(422);
            $this->reviewForm('Review Venue', $booking, null, $input, $e->errors());
        }
    }

    public function edit(string $id): void
    {
        $review = $this->editableReview((int) $id);
        $this->reviewForm('Edit Review', null, $review, ['rating' => $review['rating'], 'comment' => $review['comment']], []);
    }

    public function update(string $id): void
    {
        $this->verifyCsrf();
        $review = $this->editableReview((int) $id);
        $input = $this->reviewInput();
        try {
            (new ReviewService())->update(Auth::id(), $review['id'], $input['rating'], $input['comment']);
            Session::flash('success', 'Your review is updated.');
            $this->redirect('/customer/reviews');
        } catch (ValidationException $e) {
            if (isset($e->errors()['review'])) {
                Session::flash('error', $e->getMessage());
                $this->redirect('/customer/reviews');
            }
            http_response_code(422);
            $this->reviewForm('Edit Review', null, $review, $input, $e->errors());
        }
    }

    public function destroy(string $id): void
    {
        $this->verifyCsrf();
        $review = (new ReviewService())->customerReview(Auth::id(), (int) $id);
        if ($review === null) {
            $this->notFound();
        }
        try {
            (new ReviewService())->delete(Auth::id(), $review['id']);
            Session::flash('success', $review['in_window']
                ? "Review deleted. You can review booking #{$review['booking_id']} again until " . format_datetime($review['review_until']) . '.'
                : 'Review deleted.');
        } catch (ValidationException $e) {
            Session::flash('error', $e->getMessage());
        }
        $this->redirect('/customer/reviews');
    }

    public function moderation(): void
    {
        $this->view('admin/review-moderation', ['title' => 'Review Moderation']
            + (new ReviewService())->moderation($this->text('status')), 'dashboard');
    }

    public function remove(string $id): void
    {
        $this->verifyCsrf();
        $this->runAction(fn (ReviewService $s) => $s->remove(Auth::id(), (int) $id, $this->text('reason', false)),
            'Review removed. It is hidden from the venue page and the reviewer has been told why.');
        $this->redirect('/admin/reviews' . $this->statusQuery());
    }

    public function dismissFlag(string $id): void
    {
        $this->verifyCsrf();
        $this->runAction(fn (ReviewService $s) => $s->dismissFlag(Auth::id(), (int) $id),
            'Report dismissed. The review stays published and the owner has been told.');
        $this->redirect('/admin/reviews' . $this->statusQuery());
    }

    public function ownerIndex(): void
    {
        $venue = (int) $this->request->query('venue', 0);
        $this->view('owner/reviews', ['title' => 'Reviews']
            + (new ReviewService())->ownerReviews(Auth::id(), $venue > 0 ? $venue : null), 'dashboard');
    }

    public function respond(string $id): void
    {
        $this->verifyCsrf();
        $this->runAction(fn (ReviewService $s) => $s->respond(Auth::id(), (int) $id, $this->text('response', false)),
            'Your response is published under the review.');
        $this->redirect('/owner/reviews' . $this->venueQuery());
    }

    public function flag(string $id): void
    {
        $this->verifyCsrf();
        $this->runAction(fn (ReviewService $s) => $s->flag(Auth::id(), (int) $id, $this->text('reason', false)),
            'Review reported. The Platform Admin will check it.');
        $this->redirect('/owner/reviews' . $this->venueQuery());
    }

    /** Runs a moderation or owner action: 404 for an unknown review, otherwise flashes the result. */
    private function runAction(callable $action, string $success): void
    {
        try {
            $action(new ReviewService());
            Session::flash('success', $success);
        } catch (ValidationException $e) {
            if (($e->errors()['review'] ?? '') === 'Review not found.') {
                $this->notFound();
            }
            Session::flash('error', $e->getMessage());
        }
    }

    /** The customer's booking for the review form; 404 when it is not theirs, otherwise back to My Reviews when it cannot be reviewed. */
    private function reviewableBooking(int $bookingId): array
    {
        $booking = (new ReviewService())->reviewableBooking(Auth::id(), $bookingId);
        if ($booking === null) {
            $this->notFound();
        }
        if ($booking['review_id'] !== null && $booking['review_status'] === 'active') {
            Session::flash('info', $booking['reason'] . ' You can edit it here.');
            $this->redirect('/customer/reviews/' . $booking['review_id'] . '/edit');
        }
        if (!$booking['can_review']) {
            Session::flash('info', $booking['reason']);
            $this->redirect('/customer/reviews');
        }
        return $booking;
    }

    /** The customer's own venue review; 404 when it is not theirs, otherwise back to My Reviews when it cannot be edited. */
    private function editableReview(int $reviewId): array
    {
        $review = (new ReviewService())->customerReview(Auth::id(), $reviewId);
        if ($review === null) {
            $this->notFound();
        }
        if (!$review['can_edit']) {
            Session::flash('info', $review['status'] === 'removed'
                ? 'This review was removed by the Platform Admin, so it can no longer be edited.'
                : 'The 7-day window for editing this review closed on ' . format_datetime($review['review_until']) . '.');
            $this->redirect('/customer/reviews');
        }
        return $review;
    }

    private function reviewInput(): array
    {
        return [
            'rating'  => $this->request->input('rating'),
            'comment' => $this->text('comment', false),
        ];
    }

    private function reviewForm(string $title, ?array $booking, ?array $review, array $input, array $errors): void
    {
        $source = $booking ?? $review;
        $visit = $booking !== null ? $booking['starts_at'] : $review['visit'];
        $this->view('customer/create-review', [
            'title'          => $title,
            'target'         => 'venue',
            'bookingId'      => $booking['id'] ?? null,
            'registrationId' => null,
            'reviewId'       => $review['id'] ?? null,
            'subject'        => $source['venue_name'] . ' (' . $source['court_name'] . ', ' . format_datetime($visit) . ')',
            'reviewUntil'    => $source['review_until'],
            'rating'         => (int) $input['rating'],
            'comment'        => $input['comment'],
            'errors'         => $errors,
        ], 'dashboard');
    }

    private function statusQuery(): string
    {
        $status = $this->text('status');
        return in_array($status, ReviewService::MODERATION_FILTERS, true) ? '?status=' . $status : '';
    }

    private function venueQuery(): string
    {
        $venue = (int) $this->request->query('venue', 0);
        return $venue > 0 ? '?venue=' . $venue : '';
    }

    /** A text query or form field; anything that is not a string counts as empty. */
    private function text(string $key, bool $fromQuery = true): string
    {
        $value = $fromQuery ? $this->request->query($key, '') : $this->request->input($key, '');
        return is_string($value) ? $value : '';
    }
}
