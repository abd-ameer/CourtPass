<?php
/**
 * Court block helpers in CourtService and registration refunds in RefundService, used by coaching sessions.
 * Seed ids: court 1 has owner block 1 on day +2 18:00 and confirmed booking 3 on day +3 19:00; court 3 has booking 5
 * released on day +2 20:00; coaching block 3 is linked to session 2; registration 3 has paid payment 6 (1800).
 */
class CourtBlockRefundTest extends DatabaseTestCase
{
    private CourtService $courts;

    protected function setUp(): void
    {
        parent::setUp();
        $this->courts = new CourtService();
    }

    private function block(int $courtId, int $day, string $time, string $type = 'coaching'): int
    {
        return Database::transaction(fn () => $this->courts->addBlock($courtId, self::day($day), $time, $type, null, 7));
    }

    public function testAddedBlockTakesTheSlotOffTheGrid(): void
    {
        $id = $this->block(2, 1, '10:00');

        $row = $this->fetchRow("SELECT court_id, start_time, block_type, created_by FROM court_blocks WHERE id = {$id}");
        $this->assertSame(['court_id' => '2', 'start_time' => '10:00:00', 'block_type' => 'coaching', 'created_by' => '7'], $row);
        $grid = array_column((new BookingService())->slotGrid(2, self::day(1)), null, 'start');
        $this->assertSame('blocked', $grid['10:00']['state']);
    }

    public function testBlockRunsTheSharedConflictCheck(): void
    {
        $taken = [
            'confirmed booking' => [1, 3, '19:00'],
            'released booking'  => [3, 2, '20:00'],
            'existing block'    => [1, 2, '18:00'],
            'past slot'         => [1, -1, '10:00'],
            'court closed'      => [1, 1, '03:00'],
        ];
        foreach ($taken as $label => [$court, $day, $time]) {
            try {
                $this->block($court, $day, $time);
                $this->fail("Expected a refusal: {$label}.");
            } catch (ValidationException $e) {
                $this->assertArrayHasKey('slot', $e->errors(), $label);
            }
        }
        $this->assertSame(4, (int) $this->fetchValue('SELECT COUNT(*) FROM court_blocks'));
    }

    public function testUnknownBlockTypeIsRejected(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->block(2, 1, '10:00', 'maintenance');
    }

    public function testRemovingABlockFreesTheSlot(): void
    {
        $id = $this->block(2, 1, '10:00', 'owner');
        Database::transaction(fn () => $this->courts->removeBlock($id));

        $this->assertSame(0, (int) $this->fetchValue("SELECT COUNT(*) FROM court_blocks WHERE id = {$id}"));
        $grid = array_column((new BookingService())->slotGrid(2, self::day(1)), null, 'start');
        $this->assertSame('available', $grid['10:00']['state']);

        $this->expectException(ValidationException::class);
        Database::transaction(fn () => $this->courts->removeBlock($id));
    }

    public function testACoachingBlockStillLinkedToItsSessionCannotBeRemoved(): void
    {
        try {
            Database::transaction(fn () => $this->courts->removeBlock(3));
            $this->fail('The session link must be cleared first.');
        } catch (mysqli_sql_exception $e) {
            $this->assertSame(1451, $e->getCode());
        }
        $this->assertSame(1, (int) $this->fetchValue('SELECT COUNT(*) FROM court_blocks WHERE id = 3'));
    }

    public function testRegistrationRefundIsRecordedOnce(): void
    {
        $refunds = new RefundService();
        $refund = Database::transaction(fn () => $refunds->refundRegistration(3, 100, 'session_cancelled', 7));

        $this->assertSame(['payment_id' => 6, 'percentage' => 100.0, 'amount' => 1800.0], array_diff_key($refund, ['id' => 0]));
        $this->assertSame(['reason' => 'session_cancelled', 'amount' => '1800.00', 'created_by' => '7'],
            $this->fetchRow("SELECT reason, amount, created_by FROM refunds WHERE id = {$refund['id']}"));
        $this->assertNull(Database::transaction(fn () => $refunds->refundRegistration(3, 100, 'session_cancelled', 7)));
    }

    public function testPartialAndEmptyRegistrationRefunds(): void
    {
        $refunds = new RefundService();

        $this->assertNull(Database::transaction(fn () => $refunds->refundRegistration(3, 0, 'registration_cancel', 4)));
        $this->assertNull(Database::transaction(fn () => $refunds->refundRegistration(999, 100, 'registration_cancel', 4)));
        $half = Database::transaction(fn () => $refunds->refundRegistration(3, 50, 'registration_cancel', 4));
        $this->assertSame(900.0, $half['amount']);

        $this->expectException(InvalidArgumentException::class);
        $refunds->refundRegistration(2, 100, 'not_a_reason', 4);
    }
}
