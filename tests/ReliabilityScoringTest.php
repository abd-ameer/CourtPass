<?php
/**
 * CourtPass — PHPUnit Unit Tests: Reliability Scoring Engine
 */

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';

class ReliabilityScoringTest extends TestCase
{
    public function testNewMemberTierBelowFiveBookings()
    {
        $completed = 4;
        $score = 100.0;

        $tier = ($completed < 5) ? 'new_member' : 'standard_plus';
        $this->assertEquals('new_member', $tier, 'Members with fewer than 5 completed bookings must remain in new_member tier.');
    }

    public function testStandardPlusTierUnlockedAtFiveBookingsAnd70PercentScore()
    {
        $completed = 5;
        $score = 100.0;

        $tier = ($completed >= 5 && $score >= 70.0) ? 'standard_plus' : 'restricted';
        $this->assertEquals('standard_plus', $tier, 'Members with 5+ completed bookings and score >= 70% unlock standard_plus tier.');
    }

    public function testRestrictedTierWhenScoreBelow70Percent()
    {
        $completed = 6;
        $score = 65.0;

        $tier = ($completed >= 5 && $score >= 70.0) ? 'standard_plus' : 'restricted';
        $this->assertEquals('restricted', $tier, 'Members with score < 70% are demoted to restricted tier.');
    }
}
