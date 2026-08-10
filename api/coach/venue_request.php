<?php
/**
 * CourtPass — API: Coach Venue Request & Owner Approval Endpoints
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/audit.php';
require_once __DIR__ . '/../../includes/notifications.php';

requireLogin(true);

$action = getVal('action');
$userId = currentUserId();
$role = currentUserRole();

// ─── Coach requests approval to operate at a venue ─────────
if ($action === 'request_venue') {
    requireRole('coach', true);
    requireCsrf(true);

    $venueId = (int)($_POST['venue_id'] ?? 0);
    if (!$venueId) {
        jsonError('Venue ID is required.');
    }

    $venue = dbFetchOne("SELECT * FROM venues WHERE id = ? AND status = 'approved'", 'i', [$venueId]);
    if (!$venue) {
        jsonError('Venue not found or not active.', 404);
    }

    // Check existing
    $existing = dbFetchOne("SELECT status FROM coach_venue_approvals WHERE coach_id = ? AND venue_id = ?", 'ii', [$userId, $venueId]);
    if ($existing) {
        jsonError("You have already submitted an approval request for this venue (Status: {$existing['status']}).");
    }

    dbQuery(
        "INSERT INTO coach_venue_approvals (coach_id, venue_id, status) VALUES (?, ?, 'pending')",
        'ii',
        [$userId, $venueId]
    );

    writeAuditLog($userId, 'coach_venue_approval', $venueId, 'coach_requested_venue_approval');

    jsonSuccess('Approval request submitted to venue owner.');
    exit;
}

// ─── Venue Owner approves or declines a coach ──────────────
if ($action === 'owner_decision') {
    requireRole('owner', true);
    requireCsrf(true);

    $approvalId = (int)($_POST['approval_id'] ?? 0);
    $decision = $_POST['decision'] ?? ''; // 'approved' or 'declined'
    $declineReason = trim($_POST['decline_reason'] ?? '');

    if (!$approvalId || !in_array($decision, ['approved', 'declined'], true)) {
        jsonError('Invalid decision parameters.');
    }

    $app = dbFetchOne("SELECT cva.*, v.owner_id, v.name as venue_name FROM coach_venue_approvals cva JOIN venues v ON cva.venue_id = v.id WHERE cva.id = ?", 'i', [$approvalId]);

    if (!$app || $app['owner_id'] !== $userId) {
        jsonError('Approval request not found or access denied.', 403);
    }

    if ($decision === 'declined' && empty($declineReason)) {
        jsonError('Mandatory reason required when declining a coach.');
    }

    dbQuery(
        "UPDATE coach_venue_approvals SET status = ?, decline_reason = ? WHERE id = ?",
        'ssi',
        [$decision, $declineReason, $approvalId]
    );

    writeAuditLog($userId, 'coach_venue_approval', $approvalId, 'owner_decided_coach_approval', [
        'decision' => $decision,
        'decline_reason' => $declineReason
    ]);

    // Mandatory Notification Event:
    // Event: Coach approved at a venue -> Coach
    // Event: Coach declined at a venue -> Coach (with reason)
    if ($decision === 'approved') {
        createNotification(
            $app['coach_id'],
            'coach_venue_approved',
            'Venue Approval Granted! 🎉',
            "You have been approved to host sessions at {$app['venue_name']}.",
            $app['venue_id'],
            'venue'
        );
    } else {
        createNotification(
            $app['coach_id'],
            'coach_venue_declined',
            'Venue Request Declined',
            "Your request to operate at {$app['venue_name']} was declined. Reason: {$declineReason}",
            $app['venue_id'],
            'venue'
        );
    }

    jsonSuccess("Coach request {$decision}.");
    exit;
}

jsonError('Invalid action.', 400);
