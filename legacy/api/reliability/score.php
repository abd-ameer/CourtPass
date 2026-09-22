<?php
/**
 * CourtPass — Reliability Scoring & No-Show Engine
 * Calculates customer reliability score and tier dynamically.
 * Trigger-on-action: evaluated on login or booking attempt.
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/audit.php';

/**
 * Trigger-on-action: Check past confirmed bookings that were not completed/checked-in and are past slot time.
 * If any exist, mark them as 'no_show', write audit log, and recalculate reliability.
 *
 * @param int $customerId
 */
function checkAndMarkNoShows(int $customerId): void {
    // Find confirmed bookings for this customer where slot_date + slot_start is in the past (by at least 1 hour past end)
    $pastBookings = dbFetchAll(
        "SELECT id, court_id, slot_date, slot_start, slot_end 
         FROM bookings 
         WHERE customer_id = ? 
           AND status = 'confirmed' 
           AND CONCAT(slot_date, ' ', slot_end) < NOW()",
        'i',
        [$customerId]
    );

    if (empty($pastBookings)) {
        return;
    }

    try {
        dbBeginTransaction();

        foreach ($pastBookings as $booking) {
            dbQuery(
                "UPDATE bookings SET status = 'no_show' WHERE id = ? AND status = 'confirmed'",
                'i',
                [$booking['id']]
            );

            writeAuditLog(null, 'booking', $booking['id'], 'booking_no_show', [
                'triggered_by' => 'trigger_on_action_login_or_booking',
                'customer_id' => $customerId,
                'slot_date' => $booking['slot_date'],
                'slot_start' => $booking['slot_start']
            ]);
        }

        dbCommit();

        // Recalculate customer's reliability score after detecting no-shows
        recalculateReliabilityScore($customerId, 'no_show_detected');

    } catch (Exception $e) {
        dbRollback();
        error_log('No-show check error for customer ' . $customerId . ': ' . $e->getMessage());
    }
}

/**
 * Recalculate customer reliability score & tier based on booking history.
 *
 * Score Formula:
 * Start with base 100.
 * Total activity points = Completed (10) - NoShows (25) - Irresponsible (15) - Moderate (5)
 * Normalized Score = Clamp(100 + (NoShows * -25) + (Irresponsible * -15) + (Moderate * -5), 0, 100)
 *
 * Tiers:
 * - New Member: Completed < 5 (always New Member, display "New Member")
 * - Standard+: Completed >= 5 AND Score >= 70% (Eligible for Cash-on-Arrival)
 * - Restricted: Completed >= 5 AND Score < 70% (Online only)
 *
 * @param int $customerId
 * @param string $eventType
 * @param int|null $relatedBookingId
 * @return array
 */
function recalculateReliabilityScore(int $customerId, string $eventType = 'manual_recalc', ?int $relatedBookingId = null): array {
    // Count stats directly from database
    $stats = dbFetchOne(
        "SELECT 
            SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_cnt,
            SUM(CASE WHEN status = 'no_show' THEN 1 ELSE 0 END) as noshow_cnt,
            SUM(CASE WHEN status = 'cancelled' AND cancel_reason LIKE '%irresponsible%' THEN 1 ELSE 0 END) as irresponsible_cnt,
            SUM(CASE WHEN status = 'cancelled' AND cancel_reason LIKE '%moderate%' THEN 1 ELSE 0 END) as moderate_cnt,
            SUM(CASE WHEN status = 'cancelled' AND cancel_reason LIKE '%responsible%' THEN 1 ELSE 0 END) as responsible_cnt
         FROM bookings
         WHERE customer_id = ?",
        'i',
        [$customerId]
    );

    $completed = (int)($stats['completed_cnt'] ?? 0);
    $noShows = (int)($stats['noshow_cnt'] ?? 0);
    $irresponsible = (int)($stats['irresponsible_cnt'] ?? 0);
    $moderate = (int)($stats['moderate_cnt'] ?? 0);
    $responsible = (int)($stats['responsible_cnt'] ?? 0);

    // Score calculation
    $deductions = ($noShows * 25.0) + ($irresponsible * 15.0) + ($moderate * 5.0);
    $rawScore = 100.0 - $deductions;

    // Bonus for completed bookings: +1 point per completed booking back (up to max 100)
    $score = max(0.00, min(100.00, $rawScore));

    // Determine Tier
    $tier = 'new_member';
    if ($completed >= 5) {
        $tier = ($score >= 70.00) ? 'standard_plus' : 'restricted';
    }

    // Get current stored score
    $currentRecord = dbFetchOne("SELECT score, tier FROM reliability_scores WHERE customer_id = ?", 'i', [$customerId]);

    $oldScore = $currentRecord ? (float)$currentRecord['score'] : null;
    $oldTier = $currentRecord ? $currentRecord['tier'] : null;

    if (!$currentRecord) {
        dbQuery(
            "INSERT INTO reliability_scores 
             (customer_id, score, completed_bookings, no_shows, irresponsible_cancellations, moderate_cancellations, responsible_cancellations, tier, last_calculated)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())",
            'idiiiiis',
            [$customerId, $score, $completed, $noShows, $irresponsible, $moderate, $responsible, $tier]
        );
    } else {
        dbQuery(
            "UPDATE reliability_scores SET
             score = ?,
             completed_bookings = ?,
             no_shows = ?,
             irresponsible_cancellations = ?,
             moderate_cancellations = ?,
             responsible_cancellations = ?,
             tier = ?,
             last_calculated = NOW()
             WHERE customer_id = ?",
            'diiiiisi',
            [$score, $completed, $noShows, $irresponsible, $moderate, $responsible, $tier, $customerId]
        );
    }

    // Record history if tier or score changed
    if ($oldScore !== $score || $oldTier !== $tier) {
        dbQuery(
            "INSERT INTO reliability_history (customer_id, old_score, new_score, old_tier, new_tier, event_type, related_booking_id)
             VALUES (?, ?, ?, ?, ?, ?, ?)",
            'iddsssi',
            [$customerId, $oldScore, $score, $oldTier, $tier, $eventType, $relatedBookingId]
        );
    }

    return [
        'customer_id' => $customerId,
        'score' => $score,
        'tier' => $tier,
        'completed_bookings' => $completed,
        'no_shows' => $noShows,
        'display_tier' => getTierDisplayName($tier, $completed),
        'cash_on_arrival_eligible' => ($tier === 'standard_plus')
    ];
}

/**
 * Get customer reliability info for UI displays.
 *
 * @param int $customerId
 * @return array
 */
function getCustomerReliabilityInfo(int $customerId): array {
    // Run trigger-on-action check first
    checkAndMarkNoShows($customerId);

    $record = dbFetchOne("SELECT * FROM reliability_scores WHERE customer_id = ?", 'i', [$customerId]);

    if (!$record) {
        return recalculateReliabilityScore($customerId, 'initial_creation');
    }

    $completed = (int)$record['completed_bookings'];
    $tier = $record['tier'];

    return [
        'customer_id' => $customerId,
        'score' => (float)$record['score'],
        'tier' => $tier,
        'completed_bookings' => $completed,
        'no_shows' => (int)$record['no_shows'],
        'display_tier' => getTierDisplayName($tier, $completed),
        'display_score' => ($tier === 'new_member') ? 'New Member' : number_format((float)$record['score'], 1) . '%',
        'cash_on_arrival_eligible' => ($tier === 'standard_plus')
    ];
}

/**
 * Get human-friendly tier display label.
 */
function getTierDisplayName(string $tier, int $completedBookings): string {
    if ($tier === 'new_member' || $completedBookings < 5) {
        return 'New Member (' . $completedBookings . '/5 completed)';
    }
    if ($tier === 'restricted') {
        return 'Restricted (Online Only)';
    }
    return 'Standard+ (Cash on Arrival Unlocked)';
}
