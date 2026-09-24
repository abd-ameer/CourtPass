<?php
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * BookingService against the seed data. Seed ids used here:
 * customers 4 saman (standard), 5 ruwan (new member), 6 dinesh (restricted); owners 2 kamal (venues 1, 2), 3 nimal (no courts);
 * courts 1 and 2 futsal (5000), 3 badminton (2000), 5 table tennis, 6 squash (3000);
 * bookings 1 completed, 3 confirmed online (paid 5000), 4 pending cash, 5 released online.
 */
class BookingServiceTest extends DatabaseTestCase
{
    private BookingService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new BookingService();
    }

    // Slot grid

    public function testSlotGridFollowsTheCourtOperatingHours(): void
    {
        $weekend = (int) date('N', strtotime(self::day(2))) >= 6;
        $starts = array_column($this->service->slotGrid(1, self::day(2)), 'start');

        $this->assertSame($weekend ? '08:00' : '06:00', $starts[0]);
        $this->assertSame($weekend ? '22:00' : '21:00', end($starts));
    }

    public function testSlotGridShowsBlocksBookingsAndReleasedSlots(): void
    {
        $owner = $this->grid(1, 2);
        $this->assertSame('blocked', $owner['18:00']['state']);
        $this->assertSame(1, $owner['18:00']['block_id']);

        $coaching = $this->grid(4, 5);
        $this->assertSame('blocked', $coaching['08:00']['state']);
        $this->assertNull($coaching['08:00']['block_id']);

        $this->assertSame('booked', $this->grid(1, 3)['19:00']['state']);
        $this->assertSame('available', $this->grid(3, 2)['20:00']['state'], 'A released slot is open to other customers.');
    }

    public function testSlotGridPriceIsTheCourtRate(): void
    {
        $slot = $this->grid(1, 1)['12:00'];

        $this->assertSame('available', $slot['state']);
        $this->assertSame(5000.0, $slot['price']);
        $this->assertNull($slot['flash_price']);
    }

    public function testSlotsInThePastOrOutsideTheBookingWindowAreUnavailable(): void
    {
        foreach ([-1, 7] as $offset) {
            $states = array_unique(array_column($this->service->slotGrid(1, self::day($offset)), 'state'));
            $this->assertSame(['unavailable'], array_values($states), "Day offset {$offset}");
        }
        foreach ($this->service->slotGrid(1, self::day(0)) as $slot) {
            if (date('Y-m-d') . " {$slot['start']}:00" <= now()) {
                $this->assertSame('unavailable', $slot['state'], "Today {$slot['start']}");
            }
        }
    }

    public function testSlotGridIsNullWhenTheCourtCannotBeBooked(): void
    {
        $this->assertNull($this->service->slotGrid(999, self::day(1)));

        $this->execute('UPDATE courts SET is_active = 0 WHERE id = 2');
        $this->assertNull($this->service->slotGrid(2, self::day(1)));

        $this->execute('UPDATE venues SET is_active = 0 WHERE id = 2');
        $this->assertNull($this->service->slotGrid(6, self::day(1)));
        $this->assertNull($this->service->courtAvailability(6));
    }

    public function testCourtTabsListOnlyActiveCourtsOfTheVenue(): void
    {
        $this->execute('UPDATE courts SET is_active = 0 WHERE id = 2');
        $page = $this->service->courtAvailability(1);

        $this->assertSame([1, 3, 4, 5], array_column($page['courts'], 'id'));
        $this->assertSame('Futsal', $page['court']['sport']);
        $this->assertSame('colombo-sports-hub', $page['court']['venue_slug']);
    }

    // Shared conflict check

    public function testAssertSlotFreeAcceptsAFreeSlot(): void
    {
        $this->assertTrue($this->checkSlotInTransaction(1, self::day(2), '19:00'));
        $this->assertTrue($this->checkSlotInTransaction(1, self::day(2), '19:00:00'));
    }

    public static function takenSlots(): array
    {
        return [
            'owner block'    => [1, 2, '18:00', 'blocked'],
            'coaching block' => [4, 5, '08:00', 'blocked'],
            'booked'         => [1, 3, '19:00', 'just been booked'],
            'released'       => [3, 2, '20:00', 'released for resale'],
            'before opening' => [1, 2, '05:00', 'not open'],
            'not on the hour' => [1, 2, '14:30', 'not open'],
            'already started' => [1, -1, '10:00', 'already started'],
        ];
    }

    #[DataProvider('takenSlots')]
    public function testAssertSlotFreeRefusesTakenOrInvalidSlots(int $court, int $dayOffset, string $time, string $message): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage($message);
        $this->checkSlotInTransaction($court, self::day($dayOffset), $time);
    }

    public function testAssertSlotFreeNeverCommitsTheCallersTransaction(): void
    {
        try {
            Database::transaction(function (): void {
                (new CourtService())->lockCourt(1);
                $this->execute("INSERT INTO court_blocks (court_id, block_date, start_time, block_type, created_by) VALUES (1, '" . self::day(1) . "', '09:00:00', 'owner', 2)");
                $this->service->assertSlotFree(1, self::day(1), '10:00');
                throw new RuntimeException('roll back');
            });
        } catch (RuntimeException $e) {
            $this->assertSame('roll back', $e->getMessage());
        }

        $this->assertSame(0, (int) $this->fetchValue("SELECT COUNT(*) FROM court_blocks WHERE court_id = 1 AND block_date = '" . self::day(1) . "'"));
    }

    public function testConflictCheckExpiresAStaleHoldOnItsOwnSlot(): void
    {
        $this->insertBooking(30, 5, 2, self::day(1) . ' 10:00:00', 'online', 'pending_payment', false, 'DATE_SUB(NOW(), INTERVAL 1 MINUTE)');

        $this->assertTrue($this->checkSlotInTransaction(2, self::day(1), '10:00'));
        $this->assertSame('expired', $this->fetchValue('SELECT status FROM bookings WHERE id = 30'));
    }

    // Create

    public function testCashBookingForAStandardCustomerWaitsForTheOwner(): void
    {
        $id = $this->service->create(4, 1, self::day(1), '10:00', 'cash_on_arrival');
        $booking = $this->fetchRow("SELECT * FROM bookings WHERE id = {$id}");

        $this->assertSame('pending', $booking['status']);
        $this->assertSame('cash_on_arrival', $booking['payment_method']);
        $this->assertSame('5000.00', $booking['amount']);
        $this->assertNull($booking['pending_expires_at']);
        $this->assertSame(1, (int) $this->fetchValue("SELECT COUNT(*) FROM audit_log WHERE event_type = 'booking.created' AND entity_id = {$id} AND actor_id = 4"));
        $this->assertSame(1, (int) $this->fetchValue("SELECT COUNT(*) FROM notifications WHERE user_id = 4 AND link_url = '/customer/bookings/{$id}'"));
        $this->assertSame(1, (int) $this->fetchValue("SELECT COUNT(*) FROM notifications WHERE user_id = 2 AND type = 'booking_request' AND link_url = '/owner/bookings/{$id}'"));
    }

    public function testOnlineBookingHoldsTheSlotForTenMinutes(): void
    {
        $id = $this->service->create(5, 1, self::day(1), '11:00', 'online');
        $booking = $this->fetchRow("SELECT status, TIMESTAMPDIFF(SECOND, NOW(), pending_expires_at) AS seconds_left FROM bookings WHERE id = {$id}");

        $this->assertSame('pending_payment', $booking['status']);
        $this->assertGreaterThanOrEqual(590, (int) $booking['seconds_left']);
        $this->assertLessThanOrEqual(600, (int) $booking['seconds_left']);
        $this->assertSame(0, (int) $this->fetchValue("SELECT COUNT(*) FROM notifications WHERE user_id = 2 AND link_url = '/owner/bookings/{$id}'"));
    }

    public static function customersBelowStandard(): array
    {
        return ['new member' => [5], 'restricted' => [6]];
    }

    #[DataProvider('customersBelowStandard')]
    public function testCashOnArrivalIsOnlyForStandardTier(int $customerId): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Standard tier customers only');
        $this->service->create($customerId, 1, self::day(1), '12:00', 'cash_on_arrival');
    }

    public function testASlotCannotBeBookedTwice(): void
    {
        $this->service->create(4, 1, self::day(1), '10:00', 'cash_on_arrival');

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('just been booked');
        $this->service->create(5, 1, self::day(1), '10:00', 'online');
    }

    public static function outsideWindow(): array
    {
        return ['seven days ahead' => [7], 'yesterday' => [-1]];
    }

    #[DataProvider('outsideWindow')]
    public function testBookingsAreOpenFromTodayToSixDaysAhead(int $dayOffset): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('up to 6 days ahead');
        $this->service->create(4, 1, self::day($dayOffset), '10:00', 'online');
    }

    public function testAReleasedSlotCanBeBookedOnlineOnly(): void
    {
        try {
            $this->service->create(4, 3, self::day(2), '20:00', 'cash_on_arrival');
            $this->fail('Cash on a released slot should be refused.');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('payment_method', $e->errors());
        }

        $id = $this->service->create(4, 3, self::day(2), '20:00', 'online');
        $this->assertSame('pending_payment', $this->fetchValue("SELECT status FROM bookings WHERE id = {$id}"));
        $this->assertSame('released', $this->fetchValue('SELECT status FROM bookings WHERE id = 5'));
    }

    public function testAnInactiveOrUnknownCourtCannotBeBooked(): void
    {
        $this->execute('UPDATE courts SET is_active = 0 WHERE id = 5');

        foreach ([5, 999] as $court) {
            try {
                $this->service->create(4, $court, self::day(1), '10:00', 'online');
                $this->fail("Court {$court} should be refused.");
            } catch (ValidationException $e) {
                $this->assertStringContainsString('not taking bookings', $e->getMessage());
            }
        }
        $this->assertNull($this->service->draft(4, 5, self::day(1), '10:00'));
    }

    public function testDraftOffersCashOnlyWhenTheCustomerAndSlotAllowIt(): void
    {
        $standard = $this->service->draft(4, 1, self::day(1), '15:00');
        $this->assertTrue($standard['cash_allowed']);
        $this->assertSame(92.0, $standard['score']);
        $this->assertSame('16:00', $standard['end']);

        $newMember = $this->service->draft(5, 1, self::day(1), '15:00');
        $this->assertFalse($newMember['cash_allowed']);
        $this->assertNull($newMember['score']);

        $released = $this->service->draft(4, 3, self::day(2), '20:00');
        $this->assertFalse($released['cash_allowed']);
        $this->assertStringContainsString('released', $released['cash_note']);
    }

    // Read

    public function testCustomersSeeOnlyTheirOwnBookings(): void
    {
        $this->assertSame([4, 3, 1], array_column($this->service->customerBookings(4), 'id'));
        $this->assertNull($this->service->customerBooking(4, 2));
        $this->assertNull($this->service->customerBooking(4, 999));
        $this->assertSame(3, $this->service->customerBooking(4, 3)['id']);
    }

    public function testOwnersSeeOnlyBookingsAtTheirOwnVenues(): void
    {
        $this->assertCount(6, $this->service->ownerBookings(2));
        $this->assertSame([], $this->service->ownerBookings(3));
        $this->assertNull($this->service->ownerBooking(3, 1));
        $this->assertTrue($this->service->ownerBooking(2, 4)['can_decide']);
        $this->assertTrue($this->service->ownerBooking(2, 3)['can_owner_cancel']);
    }

    public static function cancellationBrackets(): array
    {
        // Slots start on the hour, so a slot N hours ahead is between N - 1 and N hours away.
        return [
            'online over 48h'  => [50, 'online', 100, 2000.0, null],
            'online 12 to 48h' => [47, 'online', 50, 1000.0, null],
            'online at 12h'    => [13, 'online', 50, 1000.0, null],
            'online under 12h' => [11, 'online', 0, 0.0, null],
            'cash over 48h'    => [50, 'cash_on_arrival', null, null, 'responsible'],
            'cash 12 to 48h'   => [30, 'cash_on_arrival', null, null, 'moderate'],
            'cash under 12h'   => [6, 'cash_on_arrival', null, null, 'irresponsible'],
        ];
    }

    #[DataProvider('cancellationBrackets')]
    public function testCancellationTermsFollowTheTimeBrackets(int $hoursAhead, string $method, ?int $percent, ?float $amount, ?string $class): void
    {
        $this->insertBooking(30, 4, 2, $this->slotIn($hoursAhead), $method, 'confirmed', $method === 'online');
        $terms = $this->service->customerBooking(4, 30)['cancellation'];

        $this->assertSame($percent, $terms['refund_percent']);
        $this->assertSame($amount, $terms['refund_amount']);
        $this->assertSame($class, $terms['class']);
    }

    // Update (owner)

    public function testOwnerConfirmsAPendingCashBooking(): void
    {
        $this->service->confirm(2, 4);

        $booking = $this->fetchRow('SELECT status, confirmed_at FROM bookings WHERE id = 4');
        $this->assertSame('confirmed', $booking['status']);
        $this->assertNotNull($booking['confirmed_at']);
        $this->assertSame(1, (int) $this->fetchValue("SELECT COUNT(*) FROM audit_log WHERE event_type = 'booking.confirmed' AND entity_id = 4 AND actor_id = 2"));
        $this->assertSame(1, (int) $this->fetchValue("SELECT COUNT(*) FROM notifications WHERE user_id = 4 AND type = 'booking_confirmed' AND link_url = '/customer/bookings/4'"));
    }

    public static function bookingsOwnerCannotConfirm(): array
    {
        return ['confirmed' => [3, 'is confirmed'], 'completed' => [1, 'is completed'], 'released' => [5, 'is released']];
    }

    #[DataProvider('bookingsOwnerCannotConfirm')]
    public function testOwnerCanOnlyConfirmPendingBookings(int $bookingId, string $message): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage($message);
        $this->service->confirm(2, $bookingId);
    }

    public function testOwnerCannotConfirmAnUnpaidOnlineHold(): void
    {
        $id = $this->service->create(5, 1, self::day(1), '09:00', 'online');

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('awaiting payment');
        $this->service->confirm(2, $id);
    }

    public function testRejectNeedsAReasonAndFreesTheSlot(): void
    {
        try {
            $this->service->reject(2, 4, '   ');
            $this->fail('A blank reason should be refused.');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('reason', $e->errors());
        }

        $this->service->reject(2, 4, '  Court resurfacing  ');

        $booking = $this->fetchRow('SELECT status, rejection_reason FROM bookings WHERE id = 4');
        $this->assertSame('rejected', $booking['status']);
        $this->assertSame('Court resurfacing', $booking['rejection_reason']);
        $this->assertSame('{"reason":"Court resurfacing"}', $this->fetchValue("SELECT details FROM audit_log WHERE event_type = 'booking.rejected' AND entity_id = 4"));
        $this->assertSame('available', $this->grid(6, 4)['10:00']['state']);
    }

    public function testOwnerCancelRefundsAnOnlineBookingInFull(): void
    {
        $this->service->cancelByOwner(2, 3, 'Floodlight failure');

        $booking = $this->fetchRow('SELECT status, cancelled_by, cancel_reason, cancellation_class FROM bookings WHERE id = 3');
        $this->assertSame(['cancelled', '2', 'Floodlight failure', null], array_values($booking));

        $refund = $this->fetchRow('SELECT reason, percentage, amount, created_by FROM refunds WHERE payment_id = 2');
        $this->assertSame(['owner_cancel', '100.00', '5000.00', '2'], array_values($refund));
    }

    public function testOwnerCancelOfACashBookingHasNoRefundOrClass(): void
    {
        $this->service->confirm(2, 4);
        $this->service->cancelByOwner(2, 4, 'Private event');

        $this->assertSame('cancelled', $this->fetchValue('SELECT status FROM bookings WHERE id = 4'));
        $this->assertNull($this->fetchValue('SELECT cancellation_class FROM bookings WHERE id = 4'));
        $this->assertSame(0, (int) $this->fetchValue('SELECT COUNT(*) FROM refunds'));
    }

    public function testOwnersCannotActOnAnotherOwnersBooking(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('not found');
        $this->service->confirm(3, 4);
    }

    public function testOwnersCannotActOnceTheSlotHasStarted(): void
    {
        $this->insertBooking(30, 4, 2, date('Y-m-d') . ' 00:00:00', 'cash_on_arrival', 'confirmed');

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('already started');
        $this->service->cancelByOwner(2, 30, 'Too late');
    }

    // Delete (customer cancel)

    public static function onlineRefunds(): array
    {
        return ['over 48h' => [50, '100.00', '2000.00'], '12 to 48h' => [30, '50.00', '1000.00'], 'under 12h' => [6, null, null]];
    }

    #[DataProvider('onlineRefunds')]
    public function testCustomerCancelRefundsAnOnlineBookingByBracket(int $hoursAhead, ?string $percent, ?string $amount): void
    {
        $this->insertBooking(30, 4, 2, $this->slotIn($hoursAhead), 'online', 'confirmed', true);
        $this->service->cancelByCustomer(4, 30, 'Personal / Work Emergency');

        $booking = $this->fetchRow('SELECT status, cancelled_by, cancel_reason, cancellation_class FROM bookings WHERE id = 30');
        $this->assertSame(['cancelled', '4', 'Personal / Work Emergency', null], array_values($booking));

        $refund = $this->fetchRow('SELECT r.reason, r.percentage, r.amount FROM refunds r JOIN payments p ON p.id = r.payment_id WHERE p.booking_id = 30');
        if ($percent === null) {
            $this->assertNull($refund);
        } else {
            $this->assertSame(['customer_cancel', $percent, $amount], array_values($refund));
        }
    }

    public static function cashClasses(): array
    {
        return [
            'pending over 48h'        => [50, 'pending', 'responsible'],
            'confirmed 12 to 48h'     => [30, 'confirmed', 'moderate'],
            'pending under 12h'       => [6, 'pending', 'irresponsible'],
        ];
    }

    #[DataProvider('cashClasses')]
    public function testCustomerCancelOfACashBookingStoresItsClass(int $hoursAhead, string $status, string $class): void
    {
        $this->insertBooking(30, 4, 2, $this->slotIn($hoursAhead), 'cash_on_arrival', $status);
        $result = $this->service->cancelByCustomer(4, 30, null);

        $this->assertSame($class, $result['class']);
        $this->assertSame($class, $this->fetchValue('SELECT cancellation_class FROM bookings WHERE id = 30'));
        $this->assertSame(0, (int) $this->fetchValue('SELECT COUNT(*) FROM refunds'));
        $this->assertSame('0', $this->fetchValue('SELECT irresponsible_cancel_count FROM customer_profiles WHERE customer_id = 4'), 'Reliability counters are recalculated later.');
    }

    public function testCustomerCancelNotifiesTheOwnerAndWritesTheAuditLog(): void
    {
        $this->service->cancelByCustomer(4, 4, null);

        $this->assertSame(1, (int) $this->fetchValue("SELECT COUNT(*) FROM notifications WHERE user_id = 2 AND type = 'booking_cancelled' AND link_url = '/owner/bookings/4'"));
        $details = json_decode($this->fetchValue("SELECT details FROM audit_log WHERE event_type = 'booking.cancelled' AND entity_id = 4"), true);
        $this->assertSame('customer', $details['by']);
        $this->assertSame('responsible', $details['cancellation_class']);
    }

    public function testCustomersCannotCancelHoldsFinishedReleasedOrStartedBookings(): void
    {
        $hold = $this->service->create(5, 1, self::day(1), '10:00', 'online');
        $this->insertBooking(30, 4, 2, date('Y-m-d') . ' 00:00:00', 'cash_on_arrival', 'confirmed');

        foreach ([[5, $hold], [4, 1], [5, 5], [4, 30]] as [$customer, $booking]) {
            try {
                $this->service->cancelByCustomer($customer, $booking, null);
                $this->fail("Booking {$booking} should not be cancellable.");
            } catch (ValidationException $e) {
                $this->assertStringContainsString('can no longer be cancelled', $e->getMessage());
            }
        }
    }

    public function testCustomersCannotCancelAnotherCustomersBooking(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('not found');
        $this->service->cancelByCustomer(5, 3, null);
    }

    public function testACancelledSlotCanBeBookedAgain(): void
    {
        $this->service->cancelByCustomer(4, 4, null);

        $id = $this->service->create(4, 6, self::day(4), '10:00', 'cash_on_arrival');
        $this->assertSame('pending', $this->fetchValue("SELECT status FROM bookings WHERE id = {$id}"));
    }

    // Expiry

    public function testExpireStaleExpiresUnpaidHoldsAndUnansweredRequestsOnce(): void
    {
        $this->insertBooking(30, 5, 2, self::day(1) . ' 10:00:00', 'online', 'pending_payment', false, 'DATE_SUB(NOW(), INTERVAL 1 MINUTE)');
        $this->insertBooking(31, 5, 2, self::day(1) . ' 11:00:00', 'online', 'pending_payment', false, 'DATE_ADD(NOW(), INTERVAL 5 MINUTE)');
        $this->insertBooking(32, 4, 2, date('Y-m-d') . ' 00:00:00', 'cash_on_arrival', 'pending');

        $this->assertSame(2, $this->service->expireStale());
        $this->assertSame(0, $this->service->expireStale());

        $this->assertSame('expired', $this->fetchValue('SELECT status FROM bookings WHERE id = 30'));
        $this->assertSame('pending_payment', $this->fetchValue('SELECT status FROM bookings WHERE id = 31'));
        $this->assertSame('expired', $this->fetchValue('SELECT status FROM bookings WHERE id = 32'));
        $this->assertSame(2, (int) $this->fetchValue("SELECT COUNT(*) FROM audit_log WHERE event_type = 'booking.expired' AND actor_id IS NULL"));
        $this->assertSame(2, (int) $this->fetchValue("SELECT COUNT(*) FROM notifications WHERE type = 'booking_expired'"));
    }

    // Helpers

    /** Slot grid for a court keyed by start time. */
    private function grid(int $courtId, int $dayOffset): array
    {
        return array_column($this->service->slotGrid($courtId, self::day($dayOffset)), null, 'start');
    }

    /** Runs the shared conflict check the way C and B call it: inside a transaction, after the court lock. */
    private function checkSlotInTransaction(int $courtId, string $date, string $time): bool
    {
        return Database::transaction(function () use ($courtId, $date, $time): bool {
            (new CourtService())->lockCourt($courtId);
            $this->service->assertSlotFree($courtId, $date, $time);
            return true;
        });
    }

    /** Start of the hour that is $hours from now, as a DATETIME string. */
    private function slotIn(int $hours): string
    {
        return date('Y-m-d H:00:00', time() + $hours * 3600);
    }

    /** Inserts a booking at a chosen time (bypassing the service rules) with an optional paid payment. */
    private function insertBooking(int $id, int $customerId, int $courtId, string $startsAt, string $method, string $status, bool $paid = false, string $expiresAt = 'NULL'): void
    {
        [$date, $time] = explode(' ', $startsAt);
        $confirmedAt = $status === 'confirmed' ? 'NOW()' : 'NULL';
        $this->execute("INSERT INTO bookings (id, customer_id, court_id, slot_date, start_time, amount, payment_method, status, pending_expires_at, confirmed_at)
                        VALUES ({$id}, {$customerId}, {$courtId}, '{$date}', '{$time}', 2000, '{$method}', '{$status}', {$expiresAt}, {$confirmedAt})");
        if ($paid) {
            $this->execute("INSERT INTO payments (purpose, booking_id, order_id, amount, status, payhere_payment_id, paid_at)
                            VALUES ('booking', {$id}, 'BKG-{$id}', 2000, 'paid', 'PH-TEST-{$id}', NOW())");
        }
    }
}
