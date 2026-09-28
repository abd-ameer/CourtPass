<?php
/**
 * BookingService::paySimulated and the owner-only block labels on the slot grid.
 * Seed ids: customers 4 saman, 5 ruwan; owner 2 kamal; court 3 badminton (2000); booking 4 pending cash.
 */
class BookingPaymentTest extends DatabaseTestCase
{
    private BookingService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new BookingService();
    }

    public function testPayingAHeldBookingRecordsThePaymentAndConfirmsIt(): void
    {
        $id = $this->service->create(5, 3, self::day(2), '10:00', 'online');

        $this->service->paySimulated(5, $id);

        $row = $this->fetchRow("SELECT status, confirmed_at, pending_expires_at FROM bookings WHERE id = {$id}");
        $this->assertSame('confirmed', $row['status']);
        $this->assertNotNull($row['confirmed_at']);
        $this->assertNull($row['pending_expires_at']);

        $payment = $this->fetchRow("SELECT * FROM payments WHERE booking_id = {$id}");
        $this->assertSame('booking', $payment['purpose']);
        $this->assertSame('paid', $payment['status']);
        $this->assertSame("BKG-{$id}", $payment['order_id']);
        $this->assertSame("SIM-BKG-{$id}", $payment['payhere_payment_id']);
        $this->assertSame(2000.0, (float) $payment['amount']);

        $this->assertSame(1, (int) $this->fetchValue("SELECT COUNT(*) FROM audit_log WHERE event_type = 'booking.paid' AND entity_id = {$id}"));
        $this->assertSame(1, (int) $this->fetchValue("SELECT COUNT(*) FROM notifications WHERE user_id = 5 AND type = 'booking_paid'"));
        $this->assertSame(1, (int) $this->fetchValue("SELECT COUNT(*) FROM notifications WHERE user_id = 2 AND type = 'booking_paid'"));
        $this->assertFalse($this->service->customerBooking(5, $id)['can_pay']);
    }

    public function testAPaidBookingIsRefundedInFullWhenCancelledEarly(): void
    {
        $id = $this->service->create(5, 3, self::day(4), '10:00', 'online');
        $this->service->paySimulated(5, $id);

        $result = $this->service->cancelByCustomer(5, $id, null);

        $this->assertSame(100, (int) $result['refund']['percentage']);
        $this->assertSame(2000.0, (float) $result['refund']['amount']);
    }

    public function testAnExpiredHoldCannotBePaid(): void
    {
        $id = $this->service->create(5, 3, self::day(2), '11:00', 'online');
        $this->execute("UPDATE bookings SET pending_expires_at = DATE_SUB(NOW(), INTERVAL 1 MINUTE) WHERE id = {$id}");

        try {
            $this->service->paySimulated(5, $id);
            $this->fail('An expired hold was paid.');
        } catch (ValidationException $e) {
            $this->assertStringContainsString('expired', $e->getMessage());
        }
        $this->assertSame(0, (int) $this->fetchValue("SELECT COUNT(*) FROM payments WHERE booking_id = {$id}"));
    }

    public function testOnlyTheCustomersOwnHeldBookingCanBePaid(): void
    {
        $id = $this->service->create(5, 3, self::day(2), '12:00', 'online');

        foreach ([[4, $id, 'Booking not found.'], [4, 4, 'so it cannot be paid']] as [$customer, $booking, $message]) {
            try {
                $this->service->paySimulated($customer, $booking);
                $this->fail("Booking {$booking} was paid by customer {$customer}.");
            } catch (ValidationException $e) {
                $this->assertStringContainsString($message, $e->getMessage());
            }
        }
        $this->assertSame('pending_payment', $this->fetchValue("SELECT status FROM bookings WHERE id = {$id}"));
    }

    public function testBlockLabelsAreAddedOnlyForTheOwnerView(): void
    {
        $public = array_column($this->service->slotGrid(4, self::day(5)), null, 'start');
        $this->assertArrayNotHasKey('block_label', $public['08:00']);

        $owner = array_column($this->service->slotGrid(4, self::day(5), true), null, 'start');
        $this->assertSame('Coaching: Ashan Weerasinghe', $owner['08:00']['block_label']);
        $this->assertArrayNotHasKey('block_label', $owner['09:00']);

        $block = array_column($this->service->slotGrid(1, self::day(2), true), null, 'start')['18:00'];
        $this->assertSame('Owner block: School tournament', $block['block_label']);
    }
}
