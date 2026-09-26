<?php
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * ReviewService: venue review CRUD, admin removal, owner responses and owner reports.
 * Seed ids: owner 2 kamal (venues 1 and 2), owner 3 nimal, admin 1, customer 4 saman, customer 5 ruwan.
 * Booking 1 (saman, venue 1) is completed and checked in 3 days ago; review 1 is saman's review of it
 * with kamal's response; review 2 is saman's coach review; booking 3 is confirmed with no check-in.
 * Test bookings use ids from 100.
 */
class ReviewServiceTest extends DatabaseTestCase
{
    private const ADMIN = 1;
    private const OWNER = 2;
    private const OTHER_OWNER = 3;
    private const SAMAN = 4;
    private const RUWAN = 5;

    private ReviewService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ReviewService();
    }

    // Create

    public function testCreatesAReviewForACheckedInBooking(): void
    {
        $this->insertCheckedIn(100, '-2 days');

        $id = $this->service->create(self::SAMAN, 100, '4', '  Good lighting and quick check-in.  ');

        $row = $this->fetchRow("SELECT * FROM reviews WHERE id = {$id}");
        $this->assertSame(['4', '1', '100', '4', 'Good lighting and quick check-in.', 'active'],
            [$row['reviewer_id'], $row['venue_id'], $row['booking_id'], $row['rating'], $row['comment'], $row['status']]);
        $this->assertNull($row['coach_id']);

        $audit = $this->fetchRow("SELECT * FROM audit_log WHERE event_type = 'review.created'");
        $this->assertSame([self::SAMAN, 'review', $id, 'active'],
            [(int) $audit['actor_id'], $audit['entity_type'], (int) $audit['entity_id'], $audit['new_status']]);

        $note = $this->fetchRow("SELECT * FROM notifications WHERE type = 'review_posted' ORDER BY id DESC LIMIT 1");
        $this->assertSame(self::OWNER, (int) $note['user_id']);
        $this->assertSame('/owner/reviews', $note['link_url']);
        $this->assertStringContainsString('4-star review from Saman Fernando', $note['message']);
    }

    public function testRefusedWithoutACheckIn(): void
    {
        $this->assertRefused(fn () => $this->service->create(self::SAMAN, 3, 5, 'Great.'), 'review', 'once the venue has checked you in');
        $this->assertFalse($this->service->reviewableBooking(self::SAMAN, 3)['can_review']);
    }

    public function testTheWindowIsSevenDaysFromTheCheckIn(): void
    {
        $this->insertCheckedIn(100, '-9 days', date('Y-m-d H:i:s', strtotime('-7 days -1 minute')));
        $this->insertCheckedIn(101, '-8 days', date('Y-m-d H:i:s', strtotime('-7 days +1 minute')), 4);

        $this->assertRefused(fn () => $this->service->create(self::SAMAN, 100, 5, 'Late.'), 'review', 'review window for booking #100 closed');
        $this->assertIsInt($this->service->create(self::SAMAN, 101, 5, 'Just in time.'));
        $this->assertSame([], $this->service->reviewableBookingIds(self::SAMAN));
    }

    public function testOneReviewPerBooking(): void
    {
        $this->assertRefused(fn () => $this->service->create(self::SAMAN, 1, 5, 'Again.'), 'review', 'already reviewed booking #1');
        $this->assertSame(1, $this->service->reviewableBooking(self::SAMAN, 1)['review_id']);
    }

    public function testAnotherCustomersBookingIsNotFound(): void
    {
        $this->insertCheckedIn(100, '-1 day');
        $this->assertRefused(fn () => $this->service->create(self::RUWAN, 100, 5, 'Not mine.'), 'booking', 'Booking not found.');
        $this->assertNull($this->service->reviewableBooking(self::RUWAN, 100));
    }

    public static function invalidContent(): array
    {
        return [
            'rating 0'         => [0, 'Fine.', 'rating'],
            'rating 6'         => [6, 'Fine.', 'rating'],
            'rating text'      => ['five', 'Fine.', 'rating'],
            'rating decimal'   => ['4.5', 'Fine.', 'rating'],
            'empty comment'    => [4, '   ', 'comment'],
            'long comment'     => [4, str_repeat('a', 1001), 'comment'],
        ];
    }

    #[DataProvider('invalidContent')]
    public function testContentIsValidated(mixed $rating, string $comment, string $field): void
    {
        $this->insertCheckedIn(100, '-1 day');
        $this->assertRefused(fn () => $this->service->create(self::SAMAN, 100, $rating, $comment), $field, '');
        $this->assertNull($this->fetchRow('SELECT 1 FROM reviews WHERE booking_id = 100'));
    }

    public function testAThousandCharacterCommentIsAccepted(): void
    {
        $this->insertCheckedIn(100, '-1 day');
        $id = $this->service->create(self::SAMAN, 100, 1, str_repeat('é', 1000));
        $this->assertSame(1000, mb_strlen($this->fetchValue("SELECT comment FROM reviews WHERE id = {$id}")));
    }

    // Update

    public function testUpdatesOwnReviewInsideTheWindow(): void
    {
        $this->service->update(self::SAMAN, 1, 3, 'Courts were fine, parking was hard.');

        $this->assertSame(['3', 'Courts were fine, parking was hard.'], array_values($this->fetchRow('SELECT rating, comment FROM reviews WHERE id = 1')));
        $details = json_decode($this->fetchValue("SELECT details FROM audit_log WHERE event_type = 'review.updated'"), true);
        $this->assertSame(['rating' => 5, 'comment' => 'Clean courts and staff were helpful.'], $details['old']);
        $this->assertSame(3, $details['new']['rating']);
    }

    public function testUpdateIsRefusedAfterTheWindow(): void
    {
        $this->execute("UPDATE check_ins SET checked_in_at = DATE_SUB(NOW(), INTERVAL 8 DAY) WHERE booking_id = 1");

        $this->assertRefused(fn () => $this->service->update(self::SAMAN, 1, 3, 'Too late.'), 'review', 'window for editing this review closed');
        $this->assertFalse($this->service->customerReview(self::SAMAN, 1)['can_edit']);
        $this->assertSame('5', $this->fetchValue('SELECT rating FROM reviews WHERE id = 1'));
    }

    public function testUpdateIsRefusedForAnotherCustomerAndForCoachReviews(): void
    {
        $this->assertRefused(fn () => $this->service->update(self::RUWAN, 1, 1, 'Not mine.'), 'review', 'Review not found.');
        $this->assertRefused(fn () => $this->service->update(self::SAMAN, 2, 1, 'Coach review.'), 'review', 'Review not found.');
        $this->assertNull($this->service->customerReview(self::RUWAN, 1));
        $this->assertNull($this->service->customerReview(self::SAMAN, 2));
    }

    public function testARemovedReviewCannotBeEditedOrDeleted(): void
    {
        $this->service->remove(self::ADMIN, 1, 'Personal details in the comment.');

        $this->assertRefused(fn () => $this->service->update(self::SAMAN, 1, 4, 'Edited.'), 'review', 'removed by the Platform Admin');
        $this->assertRefused(fn () => $this->service->delete(self::SAMAN, 1), 'review', 'removed by the Platform Admin');
        $this->assertRefused(fn () => $this->service->create(self::SAMAN, 1, 4, 'Again.'), 'review', 'already reviewed');
        $this->assertSame('removed', $this->fetchValue('SELECT status FROM reviews WHERE id = 1'));
    }

    // Delete

    public function testDeleteRemovesTheRowAndTheBookingCanBeReviewedAgain(): void
    {
        $this->service->flag(self::OWNER, 1, 'Mentions a staff member by name.');

        $this->service->delete(self::SAMAN, 1);

        $this->assertNull($this->fetchRow('SELECT 1 FROM reviews WHERE id = 1'));
        $this->assertNull($this->fetchRow('SELECT 1 FROM review_flags WHERE review_id = 1'));
        $details = json_decode($this->fetchValue("SELECT details FROM audit_log WHERE event_type = 'review.deleted' AND entity_id = 1"), true);
        $this->assertSame(['booking_id' => 1, 'venue_id' => 1, 'rating' => 5, 'comment' => 'Clean courts and staff were helpful.',
            'response' => 'Thank you, see you again!', 'flags' => 1], $details);

        $this->assertSame([1], $this->service->reviewableBookingIds(self::SAMAN));
        $this->assertIsInt($this->service->create(self::SAMAN, 1, 4, 'Second visit review.'));
    }

    public function testDeleteIsRefusedForAnotherCustomer(): void
    {
        $this->assertRefused(fn () => $this->service->delete(self::RUWAN, 1), 'review', 'Review not found.');
        $this->assertRefused(fn () => $this->service->delete(self::SAMAN, 2), 'review', 'Review not found.');
        $this->assertSame(2, (int) $this->fetchValue('SELECT COUNT(*) FROM reviews'));
    }

    // Admin removal

    public function testRemovalNeedsAReason(): void
    {
        $this->assertRefused(fn () => $this->service->remove(self::ADMIN, 1, '  '), 'reason', '');
        $this->assertRefused(fn () => $this->service->remove(self::ADMIN, 1, str_repeat('x', 501)), 'reason', '');
        $this->assertSame('active', $this->fetchValue('SELECT status FROM reviews WHERE id = 1'));
    }

    public function testRemovalHidesTheReviewFromTheVenueAndTheAverage(): void
    {
        $this->assertSame([1], array_column($this->service->venueReviews(1), 'id'));
        $this->assertSame(['avg_rating' => 5.0, 'review_count' => 1], $this->service->venueRatings([1])[1]);

        $this->service->remove(self::ADMIN, 1, 'Abusive language.');

        $row = $this->fetchRow('SELECT status, removed_by, removed_reason FROM reviews WHERE id = 1');
        $this->assertSame(['removed', '1', 'Abusive language.'], array_values($row));
        $this->assertSame([], $this->service->venueReviews(1));
        $this->assertSame(['avg_rating' => null, 'review_count' => 0], $this->service->venueRatings([1])[1]);

        $note = $this->fetchRow("SELECT * FROM notifications WHERE type = 'review_removed'");
        $this->assertSame(self::SAMAN, (int) $note['user_id']);
        $this->assertStringContainsString('Abusive language.', $note['message']);
        $this->assertSame('removed', $this->fetchValue("SELECT new_status FROM audit_log WHERE event_type = 'review.removed'"));

        $this->assertRefused(fn () => $this->service->remove(self::ADMIN, 1, 'Again.'), 'review', 'already been removed');
        $this->assertRefused(fn () => $this->service->remove(self::ADMIN, 2, 'Coach review.'), 'review', 'Review not found.');
    }

    public function testRemovingAReportedReviewUpholdsTheReport(): void
    {
        $this->service->flag(self::OWNER, 1, 'Offensive.');
        $this->service->remove(self::ADMIN, 1, 'Offensive language.');

        $this->assertSame(['upheld', '1'], array_values($this->fetchRow('SELECT status, resolved_by FROM review_flags WHERE review_id = 1')));
        $this->assertTrue(json_decode($this->fetchValue("SELECT details FROM audit_log WHERE event_type = 'review.removed'"), true)['flag_upheld']);
    }

    // Owner response

    public function testOwnerRespondsOnce(): void
    {
        $this->execute('UPDATE reviews SET response_text = NULL, responded_by = NULL, responded_at = NULL WHERE id = 1');

        $this->service->respond(self::OWNER, 1, ' Thanks for visiting! ');

        $this->assertSame(['Thanks for visiting!', '2'], array_values($this->fetchRow('SELECT response_text, responded_by FROM reviews WHERE id = 1')));
        $this->assertSame(self::SAMAN, (int) $this->fetchValue("SELECT user_id FROM notifications WHERE type = 'review_response'"));
        $this->assertSame(1, (int) $this->fetchValue("SELECT COUNT(*) FROM audit_log WHERE event_type = 'review.responded'"));

        $this->assertRefused(fn () => $this->service->respond(self::OWNER, 1, 'Second.'), 'review', 'already responded');
    }

    public function testResponseRules(): void
    {
        $this->execute('UPDATE reviews SET response_text = NULL, responded_by = NULL, responded_at = NULL WHERE id = 1');

        $this->assertRefused(fn () => $this->service->respond(self::OWNER, 1, ''), 'response', '');
        $this->assertRefused(fn () => $this->service->respond(self::OTHER_OWNER, 1, 'Not my venue.'), 'review', 'Review not found.');
        $this->assertRefused(fn () => $this->service->respond(self::OWNER, 2, 'Coach review.'), 'review', 'Review not found.');
        $this->service->remove(self::ADMIN, 1, 'Spam.');
        $this->assertRefused(fn () => $this->service->respond(self::OWNER, 1, 'Late.'), 'review', 'removed');
    }

    // Owner reports

    public function testOwnerReportsAReviewAndTheAdminDismissesIt(): void
    {
        $this->service->flag(self::OWNER, 1, 'Customer was never here.');

        $this->assertSame('open', $this->fetchValue('SELECT status FROM review_flags WHERE review_id = 1'));
        $moderation = $this->service->moderation('');
        $this->assertSame('flagged', $moderation['status'], 'Open reports are shown first.');
        $this->assertSame([1], array_column($moderation['reviews'], 'id'));
        $this->assertSame('Customer was never here.', $moderation['reviews'][0]['flag_reason']);
        $this->assertRefused(fn () => $this->service->flag(self::OWNER, 1, 'Again.'), 'review', 'already reported');

        $this->service->dismissFlag(self::ADMIN, 1);

        $this->assertSame('dismissed', $this->fetchValue('SELECT status FROM review_flags WHERE review_id = 1'));
        $this->assertSame('active', $this->fetchValue('SELECT status FROM reviews WHERE id = 1'));
        $this->assertSame(self::OWNER, (int) $this->fetchValue("SELECT user_id FROM notifications WHERE type = 'review_flag_dismissed'"));
        $this->assertSame('active', $this->service->moderation('')['status']);
        $this->assertRefused(fn () => $this->service->dismissFlag(self::ADMIN, 1), 'review', 'no open report');

        $this->service->flag(self::OWNER, 1, 'New evidence.');
        $this->assertSame(2, (int) $this->fetchValue('SELECT COUNT(*) FROM review_flags WHERE review_id = 1'));
        $this->assertSame(2, (int) $this->fetchValue("SELECT COUNT(*) FROM audit_log WHERE event_type = 'review.flagged'"));
    }

    public function testReportRules(): void
    {
        $this->assertRefused(fn () => $this->service->flag(self::OWNER, 1, ''), 'reason', '');
        $this->assertRefused(fn () => $this->service->flag(self::OTHER_OWNER, 1, 'Not my venue.'), 'review', 'Review not found.');
        $this->assertRefused(fn () => $this->service->flag(self::OWNER, 2, 'Coach review.'), 'review', 'Review not found.');
        $this->assertNull($this->fetchRow('SELECT 1 FROM review_flags'));
    }

    // Reads

    public function testCustomerReviewsListVenueAndCoachReviews(): void
    {
        $this->insertCheckedIn(100, '-1 day');

        $mine = $this->service->customerReviews(self::SAMAN);

        $this->assertEqualsCanonicalizing([1, 2], array_column($mine['reviews'], 'id'));
        $byId = array_column($mine['reviews'], null, 'id');
        $this->assertTrue($byId[1]['can_edit']);
        $this->assertFalse($byId[2]['can_edit'], 'Coach reviews are read-only here.');
        $this->assertFalse($byId[2]['can_delete']);
        $this->assertSame([100], array_column($mine['to_review'], 'id'));
        $this->assertSame([100], $this->service->reviewableBookingIds(self::SAMAN));
        $this->assertSame([], $this->service->customerReviews(self::RUWAN)['reviews']);
    }

    public function testOwnerReviewsOnlyShowTheOwnersVenues(): void
    {
        $kamal = $this->service->ownerReviews(self::OWNER);
        $this->assertSame([1], array_column($kamal['reviews'], 'id'));
        $venues = array_column($kamal['venues'], null, 'id');
        $this->assertEqualsCanonicalizing([1, 2], array_keys($venues));
        $this->assertSame(5.0, $venues[1]['avg_rating']);
        $this->assertSame(0, $venues[2]['review_count']);
        $this->assertSame([], $this->service->ownerReviews(self::OWNER, 2)['reviews']);
        $this->assertNull($this->service->ownerReviews(self::OWNER, 3)['venue_id'], "Another owner's venue is ignored.");
        $this->assertSame([], $this->service->ownerReviews(self::OTHER_OWNER)['reviews']);
    }

    private function assertRefused(callable $action, string $field, string $message): void
    {
        try {
            $action();
            $this->fail('The action should have been refused.');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey($field, $e->errors());
            $this->assertStringContainsString($message, $e->errors()[$field]);
        }
    }

    /** Inserts a completed booking of saman at venue 1 and its check-in (default: at the slot start). */
    private function insertCheckedIn(int $id, string $slotOffset, ?string $checkedInAt = null, int $court = 5): void
    {
        $date = date('Y-m-d', strtotime($slotOffset));
        $checkedInAt ??= "{$date} 10:00:00";
        $this->execute("INSERT INTO bookings (id, customer_id, court_id, slot_date, start_time, amount, payment_method, status, confirmed_at)
                        VALUES ({$id}, " . self::SAMAN . ", {$court}, '{$date}', '10:00:00', 1000, 'online', 'completed', NOW())");
        $this->execute("INSERT INTO check_ins (booking_id, checked_in_by, checked_in_at) VALUES ({$id}, " . self::OWNER . ", '{$checkedInAt}')");
    }
}
