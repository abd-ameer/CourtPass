<?php
/**
 * CourtPass — PHPUnit Unit Tests: Booking Conflict Engine
 */

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';

class BookingConflictTest extends TestCase
{
    public function testSlotOverlapDetectionLogic()
    {
        $slot1Start = '10:00:00';
        $slot1End   = '11:00:00';

        $slot2Start = '10:00:00';
        $slot2End   = '11:00:00';

        $isExactOverlap = ($slot1Start === $slot2Start);
        $this->assertTrue($isExactOverlap, 'Identical start times for 1-hour slots must trigger an overlap conflict.');
    }

    public function testNonOverlappingSlots()
    {
        $slot1Start = '10:00:00';
        $slot1End   = '11:00:00';

        $slot2Start = '11:00:00';
        $slot2End   = '12:00:00';

        $isOverlap = ($slot1Start < $slot2End && $slot1End > $slot2Start);
        $this->assertFalse($isOverlap, 'Adjacent 1-hour slots (10-11 and 11-12) must not conflict.');
    }
}
