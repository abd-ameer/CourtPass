<?php
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * BookingService::completeByCheckIn and the check-in flags. Seed ids used here:
 * owner 2 kamal (venue 1, courts 1 to 5), owner 3 nimal (no courts), customer 4 saman,
 * booking 3 confirmed online at day +3 19:00. Test bookings use ids from 100 on court 5.
 *
 * Slots start on the hour, so the window always opens and closes on an hour boundary.
 * With H the current hour, a slot starting at H or H+1 is inside the window, H+2 is too early and H-1 has ended.
 */
class BookingCheckInTest extends DatabaseTestCase
{
    private const OWNER = 2;
    private const OTHER_OWNER = 3;
    private const CUSTOMER = 4;
    private const COURT = 5;

    private BookingService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new BookingService();
        // Keep each test inside one clock hour so the window edges stay where the test expects them.
        $left = 3600 - (time() % 3600);
        if ($left < 10) {
            sleep($left + 1);
        }
    }

    public static function paymentMethods(): array
    {
        return ['cash' => ['cash_on_arrival'], 'online' => ['online']];
    }

    #[DataProvider('paymentMethods')]
    public function testChecksInAStartedConfirmedBooking(string $method): void
    {
        $this->insertBooking(100, $this->slotIn(0), $method, 'confirmed');

        $booking = $this->checkIn(self::OWNER, 100);

        $this->assertSame('completed', $this->bookingStatus(100));
        $this->assertSame(100, $booking['id']);
        $this->assertSame('completed', $booking['status']);
        $this->assertFalse($booking['can_check_in']);
        $this->assertSame(self::CUSTOMER, $booking['customer_id']);
    }

    public function testWritesTheAuditEntryAndNotifiesTheCustomer(): void
    {
        $this->insertBooking(100, $this->slotIn(0), 'online', 'confirmed');

        $this->checkIn(self::OWNER, 100);

        $audit = $this->fetchRow("SELECT * FROM audit_log WHERE event_type = 'booking.completed' AND entity_id = 100");
        $this->assertSame(self::OWNER, (int) $audit['actor_id']);
        $this->assertSame('booking', $audit['entity_type']);
        $this->assertSame('confirmed', $audit['old_status']);
        $this->assertSame('completed', $audit['new_status']);
        $this->assertSame(['via' => 'check_in'], json_decode($audit['details'], true));

        $note = $this->fetchRow("SELECT * FROM notifications WHERE type = 'booking_completed'");
        $this->assertSame(self::CUSTOMER, (int) $note['user_id']);
        $this->assertSame('/customer/bookings/100', $note['link_url']);
        $this->assertStringContainsString('within 7 days', $note['message']);
    }

    public function testChecksInUpToOneHourBeforeTheStart(): void
    {
        $startsAt = $this->slotIn(1);
        $this->insertBooking(100, $startsAt, 'cash_on_arrival', 'confirmed');

        if (substr($startsAt, 0, 10) !== date('Y-m-d')) {
            // Between 23:00 and midnight the next slot is on the next date, where check-in opens at 00:00.
            $this->assertRefused(self::OWNER, 100, 'opens at');
            return;
        }
        $this->checkIn(self::OWNER, 100);
        $this->assertSame('completed', $this->bookingStatus(100));
    }

    public function testRefusedMoreThanOneHourBeforeTheStart(): void
    {
        $this->insertBooking(100, $this->slotIn(2), 'online', 'confirmed');

        $this->assertRefused(self::OWNER, 100, 'Check-in for booking #100 opens at');
        $this->assertSame('confirmed', $this->bookingStatus(100));
    }

    public function testRefusedAfterTheSlotEnds(): void
    {
        $this->insertBooking(100, $this->slotIn(-1), 'cash_on_arrival', 'confirmed');

        $this->assertRefused(self::OWNER, 100, 'so it can no longer be checked in');
        $this->assertSame('confirmed', $this->bookingStatus(100));
    }

    public function testSeedBookingDaysAheadIsNotOpenYet(): void
    {
        $this->assertRefused(self::OWNER, 3, 'Check-in for booking #3 opens at');
    }

    public function testRefusedForAnotherOwnerOrAnUnknownBooking(): void
    {
        $this->insertBooking(100, $this->slotIn(0), 'online', 'confirmed');

        $this->assertRefused(self::OTHER_OWNER, 100, 'Booking not found.');
        $this->assertRefused(self::OWNER, 999, 'Booking not found.');
        $this->assertSame('confirmed', $this->bookingStatus(100));
    }

    public static function otherStatuses(): array
    {
        return [
            'pending'              => ['cash_on_arrival', 'pending'],
            'pending payment'      => ['online', 'pending_payment'],
            'released'             => ['online', 'released'],
            'completed'            => ['online', 'completed'],
            'completed unattended' => ['online', 'completed_unattended'],
            'no-show'              => ['cash_on_arrival', 'no_show'],
            'cancelled'            => ['cash_on_arrival', 'cancelled'],
            'rejected'             => ['cash_on_arrival', 'rejected'],
            'expired'              => ['online', 'expired'],
        ];
    }

    #[DataProvider('otherStatuses')]
    public function testRefusedUnlessConfirmed(string $method, string $status): void
    {
        $this->insertBooking(100, $this->slotIn(0), $method, $status);

        $this->assertRefused(self::OWNER, 100, 'so it cannot be checked in');
        $this->assertSame($status, $this->bookingStatus(100));
        $this->assertSame(0, (int) $this->fetchValue("SELECT COUNT(*) FROM audit_log WHERE event_type = 'booking.completed'"));
    }

    public function testSecondCheckInIsRefused(): void
    {
        $this->insertBooking(100, $this->slotIn(0), 'online', 'confirmed');
        $this->checkIn(self::OWNER, 100);

        $this->assertRefused(self::OWNER, 100, 'Booking #100 is completed, so it cannot be checked in.');
        $this->assertSame(1, (int) $this->fetchValue("SELECT COUNT(*) FROM audit_log WHERE event_type = 'booking.completed'"));
        $this->assertSame(1, (int) $this->fetchValue("SELECT COUNT(*) FROM notifications WHERE type = 'booking_completed'"));
    }

    public function testRollsBackWithTheCallersTransaction(): void
    {
        $this->insertBooking(100, $this->slotIn(0), 'online', 'confirmed');

        try {
            Database::transaction(function (): void {
                $this->service->completeByCheckIn(self::OWNER, 100);
                throw new RuntimeException('Check-in record failed.');
            });
            $this->fail('The caller transaction should have thrown.');
        } catch (RuntimeException $e) {
            $this->assertSame('Check-in record failed.', $e->getMessage());
        }

        $this->assertSame('confirmed', $this->bookingStatus(100));
        $this->assertSame(0, (int) $this->fetchValue("SELECT COUNT(*) FROM audit_log WHERE event_type = 'booking.completed'"));
        $this->assertSame(0, (int) $this->fetchValue("SELECT COUNT(*) FROM notifications WHERE type = 'booking_completed'"));
    }

    public function testSwitchedOffVenueKeepsItsBookingsCheckable(): void
    {
        $this->insertBooking(100, $this->slotIn(0), 'cash_on_arrival', 'confirmed');
        $this->execute('UPDATE venues SET is_active = 0 WHERE id = 1');

        $this->checkIn(self::OWNER, 100);
        $this->assertSame('completed', $this->bookingStatus(100));
    }

    public function testCheckInFlagFollowsTheWindowAndStatus(): void
    {
        $this->insertBooking(100, $this->slotIn(0), 'online', 'confirmed');
        $this->insertBooking(101, $this->slotIn(2), 'online', 'confirmed');
        $this->insertBooking(102, $this->slotIn(-1), 'online', 'confirmed');
        $this->insertBooking(103, $this->slotIn(0), 'cash_on_arrival', 'pending', 4);

        $flags = [];
        foreach ([100, 101, 102, 103] as $id) {
            $flags[$id] = $this->service->ownerBooking(self::OWNER, $id)['can_check_in'];
        }
        $this->assertSame([100 => true, 101 => false, 102 => false, 103 => false], $flags);

        $list = array_column($this->service->ownerBookings(self::OWNER), 'can_check_in', 'id');
        $this->assertTrue($list[100]);
        $this->assertFalse($list[3]);
    }

    public function testCheckInOpensOneHourBeforeTheStartButNotBeforeTheSlotDate(): void
    {
        $this->insertBooking(100, self::day(1) . ' 17:00:00', 'online', 'confirmed');
        $this->insertBooking(101, self::day(1) . ' 00:00:00', 'online', 'confirmed');

        $this->assertSame('16:00', $this->service->ownerBooking(self::OWNER, 100)['check_in_opens']);
        $this->assertSame('00:00', $this->service->ownerBooking(self::OWNER, 101)['check_in_opens']);
    }

    /** Calls the method the way the check-in service does: inside its own transaction. */
    private function checkIn(int $ownerId, int $bookingId): array
    {
        return Database::transaction(fn (): array => $this->service->completeByCheckIn($ownerId, $bookingId));
    }

    private function assertRefused(int $ownerId, int $bookingId, string $message): void
    {
        try {
            $this->checkIn($ownerId, $bookingId);
            $this->fail("Check-in of booking {$bookingId} should have been refused.");
        } catch (ValidationException $e) {
            $this->assertStringContainsString($message, $e->errors()['booking']);
        }
    }

    private function bookingStatus(int $bookingId): string
    {
        return (string) $this->fetchValue("SELECT status FROM bookings WHERE id = {$bookingId}");
    }

    /** Start of the hour that is $hours from now, as a DATETIME string. */
    private function slotIn(int $hours): string
    {
        return date('Y-m-d H:00:00', time() + $hours * 3600);
    }

    /** Inserts a booking at a chosen time, bypassing the service rules. */
    private function insertBooking(int $id, string $startsAt, string $method, string $status, int $court = self::COURT): void
    {
        [$date, $time] = explode(' ', $startsAt);
        $expires = $status === 'pending_payment' ? 'DATE_ADD(NOW(), INTERVAL 10 MINUTE)' : 'NULL';
        $confirmedAt = in_array($status, ['confirmed', 'released', 'completed', 'completed_unattended', 'no_show'], true) ? 'NOW()' : 'NULL';
        $cancelledAt = $status === 'cancelled' ? 'NOW()' : 'NULL';
        $rejection = $status === 'rejected' ? "'Court maintenance'" : 'NULL';
        $customer = self::CUSTOMER;
        $this->execute("INSERT INTO bookings (id, customer_id, court_id, slot_date, start_time, amount, payment_method, status,
                            pending_expires_at, confirmed_at, cancelled_at, rejection_reason)
                        VALUES ({$id}, {$customer}, {$court}, '{$date}', '{$time}', 1000, '{$method}', '{$status}',
                            {$expires}, {$confirmedAt}, {$cancelledAt}, {$rejection})");
    }
}
