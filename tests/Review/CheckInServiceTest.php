<?php
/**
 * CheckInService: recording a check-in through the booking engine, and the owner's front desk.
 * Seed ids: owner 2 kamal (venue 1, courts 1 to 5; venue 2, courts 6 to 8), owner 3 nimal (no courts),
 * customer 4 saman, customer 5 ruwan, booking 3 confirmed at day +3 19:00. Test bookings use ids from 100.
 * With H the current hour, a slot starting at H is inside the check-in window and H+2 is too early.
 */
class CheckInServiceTest extends DatabaseTestCase
{
    private const OWNER = 2;
    private const OTHER_OWNER = 3;
    private const SAMAN = 4;
    private const RUWAN = 5;

    private CheckInService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new CheckInService();
        // Keep each test inside one clock hour so the window edges stay where the test expects them.
        $left = 3600 - (time() % 3600);
        if ($left < 10) {
            sleep($left + 1);
        }
    }

    // Check-in

    public function testCheckInCompletesTheBookingAndAddsTheCheckInRow(): void
    {
        $this->insertBooking(100, $this->slotIn(0), 'confirmed');

        $booking = $this->service->checkIn(self::OWNER, 100);

        $this->assertSame('completed', $booking['status']);
        $this->assertNotNull($booking['checked_in_at']);
        $this->assertSame('completed', $this->fetchValue('SELECT status FROM bookings WHERE id = 100'));
        $row = $this->fetchRow('SELECT checked_in_by, checked_in_at FROM check_ins WHERE booking_id = 100');
        $this->assertSame(self::OWNER, (int) $row['checked_in_by']);
        $this->assertSame($booking['checked_in_at'], $row['checked_in_at']);

        $audit = $this->fetchRow("SELECT * FROM audit_log WHERE event_type = 'check_in.recorded'");
        $this->assertSame(self::OWNER, (int) $audit['actor_id']);
        $this->assertSame('check_in', $audit['entity_type']);
        $this->assertSame(100, (int) $audit['entity_id']);
        $this->assertSame(1, (int) $this->fetchValue("SELECT COUNT(*) FROM audit_log WHERE event_type = 'booking.completed' AND entity_id = 100"));
        $this->assertSame(1, (int) $this->fetchValue("SELECT COUNT(*) FROM notifications WHERE type = 'booking_completed'"),
            'Only the booking engine notifies the customer.');
    }

    public function testTheWindowComesFromTheBookingEngine(): void
    {
        $this->insertBooking(100, $this->slotIn(2), 'confirmed');
        $this->assertRefused(self::OWNER, 100, 'opens at');

        $this->assertRefused(self::OWNER, 3, 'opens at');
        $this->assertNull($this->fetchRow('SELECT 1 FROM check_ins WHERE booking_id IN (3, 100)'));
    }

    public function testAnEndedBookingIsRefused(): void
    {
        $this->insertBooking(100, $this->slotIn(-1), 'confirmed');
        $this->assertRefused(self::OWNER, 100, 'can no longer be checked in');
    }

    public function testAnotherOwnersBookingIsNotFound(): void
    {
        $this->insertBooking(100, $this->slotIn(0), 'confirmed');

        $this->assertRefused(self::OTHER_OWNER, 100, 'Booking not found.');
        $this->assertRefused(self::OWNER, 999, 'Booking not found.');
        $this->assertSame('confirmed', $this->fetchValue('SELECT status FROM bookings WHERE id = 100'));
    }

    public function testASecondCheckInIsRefused(): void
    {
        $this->insertBooking(100, $this->slotIn(0), 'confirmed');
        $this->service->checkIn(self::OWNER, 100);

        $this->assertRefused(self::OWNER, 100, 'cannot be checked in');
        $this->assertSame(1, (int) $this->fetchValue('SELECT COUNT(*) FROM check_ins WHERE booking_id = 100'));
    }

    public function testOnlyConfirmedBookingsCanBeCheckedIn(): void
    {
        $this->insertBooking(100, $this->slotIn(0), 'pending', 'cash_on_arrival');
        $this->assertRefused(self::OWNER, 100, 'is pending');
    }

    public function testAFailedCheckInRowRollsBackTheCompletion(): void
    {
        $this->insertBooking(100, $this->slotIn(0), 'confirmed');
        // A stray check-in row makes the insert fail after the booking engine has completed the booking.
        $this->execute('INSERT INTO check_ins (booking_id, checked_in_by) VALUES (100, ' . self::OWNER . ')');

        $this->assertRefused(self::OWNER, 100, 'already been checked in');

        $this->assertSame('confirmed', $this->fetchValue('SELECT status FROM bookings WHERE id = 100'));
        $this->assertSame(0, (int) $this->fetchValue("SELECT COUNT(*) FROM audit_log WHERE event_type IN ('booking.completed', 'check_in.recorded')"));
        $this->assertSame(0, (int) $this->fetchValue("SELECT COUNT(*) FROM notifications WHERE type = 'booking_completed'"));
    }

    // Front desk

    public function testDeskListsTodaysBookingsByState(): void
    {
        $this->insertBooking(100, $this->slotIn(0), 'confirmed', 'online', 5);
        $this->insertBooking(101, $this->slotIn(0), 'confirmed', 'cash_on_arrival', 4, self::RUWAN);
        $this->insertBooking(102, $this->slotIn(0), 'pending', 'cash_on_arrival', 2);
        $this->insertBooking(103, $this->slotIn(0), 'confirmed', 'online', 6);
        $this->service->checkIn(self::OWNER, 103);

        $desk = $this->service->desk(self::OWNER);

        $states = array_column($desk['bookings'], 'desk_state', 'id');
        $this->assertSame([100 => 'waiting', 101 => 'waiting', 103 => 'checked_in'], $states, 'Pending requests and other days are left out.');
        $this->assertSame(['waiting' => 2, 'checked_in' => 1, 'missed' => 0], $desk['counts']);
        $this->assertTrue($desk['bookings'][0]['can_check_in']);
        $this->assertSame([], $this->service->desk(self::OTHER_OWNER)['bookings']);
    }

    public function testDeskShowsTodaysEndedBookingsAsMissed(): void
    {
        if ((int) date('G') === 0) {
            $this->markTestSkipped('The previous hour is on yesterday\'s date.');
        }
        $this->insertBooking(100, $this->slotIn(-1), 'confirmed');

        $desk = $this->service->desk(self::OWNER);

        $this->assertSame('missed', $desk['bookings'][0]['desk_state']);
        $this->assertSame(1, $desk['counts']['missed']);
        $this->assertFalse($desk['bookings'][0]['can_check_in']);
    }

    public function testDeskSearchesByNameAndById(): void
    {
        $this->insertBooking(100, $this->slotIn(0), 'confirmed', 'online', 5);
        $this->insertBooking(101, $this->slotIn(0), 'confirmed', 'online', 4, self::RUWAN);

        $this->assertSame([101], array_column($this->service->desk(self::OWNER, ' ruwan ')['bookings'], 'id'));
        $this->assertSame(2, $this->service->desk(self::OWNER, 'ruwan')['counts']['waiting'], 'Counts cover the whole day.');

        $byId = $this->service->desk(self::OWNER, '#3');
        $this->assertSame(3, $byId['found']['id'], 'An id finds a booking on another date.');
        $this->assertSame('waiting', $byId['found']['desk_state']);
        $this->assertFalse($byId['found']['can_check_in']);

        $this->assertNull($this->service->desk(self::OTHER_OWNER, '3')['found']);
        $this->assertSame('No booking #3 at your venues.', $this->service->desk(self::OTHER_OWNER, '3')['message']);
        $this->assertStringContainsString('No booking today', $this->service->desk(self::OWNER, 'nobody')['message']);
    }

    private function assertRefused(int $ownerId, int $bookingId, string $message): void
    {
        try {
            $this->service->checkIn($ownerId, $bookingId);
            $this->fail("Check-in of booking {$bookingId} should have been refused.");
        } catch (ValidationException $e) {
            $this->assertStringContainsString($message, $e->errors()['booking']);
        }
    }

    /** Start of the hour that is $hours from now, as a DATETIME string. */
    private function slotIn(int $hours): string
    {
        return date('Y-m-d H:00:00', time() + $hours * 3600);
    }

    /** Inserts a booking at a chosen time, bypassing the service rules. */
    private function insertBooking(int $id, string $startsAt, string $status, string $method = 'online', int $court = 5, int $customer = self::SAMAN): void
    {
        [$date, $time] = explode(' ', $startsAt);
        $confirmedAt = $status === 'confirmed' ? 'NOW()' : 'NULL';
        $this->execute("INSERT INTO bookings (id, customer_id, court_id, slot_date, start_time, amount, payment_method, status, confirmed_at)
                        VALUES ({$id}, {$customer}, {$court}, '{$date}', '{$time}', 1000, '{$method}', '{$status}', {$confirmedAt})");
    }
}
