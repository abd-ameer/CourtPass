<?php
/**
 * CourtPass — API: Admin Venue Actions Endpoint
 * Approves/rejects pending venues and deactivates venues (cascades to auto-cancel future bookings).
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/audit.php';
require_once __DIR__ . '/../../includes/notifications.php';

requireLogin(true);
requireRole('admin', true);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonError('Method not allowed', 405);
}

requireCsrf(true);

$action = $_POST['action'] ?? '';
$venueId = (int)($_POST['venue_id'] ?? 0);
$reason = trim($_POST['reason'] ?? '');
$adminId = currentUserId();

if (!$venueId || !in_array($action, ['approve', 'reject', 'deactivate'], true)) {
    jsonError('Invalid venue action parameters.');
}

$venue = dbFetchOne("SELECT * FROM venues WHERE id = ?", 'i', [$venueId]);
if (!$venue) {
    jsonError('Venue not found.', 404);
}

try {
    dbBeginTransaction();

    if ($action === 'approve') {
        dbQuery("UPDATE venues SET status = 'approved' WHERE id = ?", 'i', [$venueId]);
        writeAuditLog($adminId, 'venue', $venueId, 'admin_approved_venue');
        
        createNotification(
            $venue['owner_id'],
            'venue_approved',
            'Venue Approved! 🎉',
            "Your venue '{$venue['name']}' has been approved and is now live on CourtPass.",
            $venueId,
            'venue'
        );

        dbCommit();
        jsonSuccess("Venue '{$venue['name']}' approved.");

    } elseif ($action === 'reject') {
        if (empty($reason)) {
            dbRollback();
            jsonError('Mandatory reason required when rejecting a venue.');
        }

        dbQuery("UPDATE venues SET status = 'rejected', rejection_reason = ? WHERE id = ?", 'si', [$reason, $venueId]);
        writeAuditLog($adminId, 'venue', $venueId, 'admin_rejected_venue', ['reason' => $reason]);

        createNotification(
            $venue['owner_id'],
            'venue_rejected',
            'Venue Registration Declined',
            "Your venue '{$venue['name']}' was declined. Reason: {$reason}",
            $venueId,
            'venue'
        );

        dbCommit();
        jsonSuccess("Venue '{$venue['name']}' rejected.");

    } elseif ($action === 'deactivate') {
        // FORCED VENUE DEACTIVATION: Cascades to auto-cancel all future bookings at this venue
        dbQuery("UPDATE venues SET status = 'deactivated' WHERE id = ?", 'i', [$venueId]);

        $futureBookings = dbFetchAll(
            "SELECT b.id, b.customer_id, b.slot_date, b.slot_start 
             FROM bookings b 
             JOIN courts c ON b.court_id = c.id 
             WHERE c.venue_id = ? AND b.status IN ('pending', 'confirmed') AND CONCAT(b.slot_date, ' ', b.slot_start) > NOW()",
            'i',
            [$venueId]
        );

        foreach ($futureBookings as $fb) {
            dbQuery("UPDATE bookings SET status = 'cancelled', cancel_reason = 'Venue deactivated by platform admin' WHERE id = ?", 'i', [$fb['id']]);

            createNotification(
                $fb['customer_id'],
                'booking_cancelled_venue_deactivated',
                'Booking Cancelled ⚠️',
                "Your booking #{$fb['id']} was cancelled because the venue was deactivated. A full refund has been credited.",
                $fb['id'],
                'booking'
            );
        }

        writeAuditLog($adminId, 'venue', $venueId, 'admin_deactivated_venue', [
            'auto_cancelled_future_bookings' => count($futureBookings)
        ]);

        dbCommit();
        jsonSuccess("Venue deactivated. Auto-cancelled " . count($futureBookings) . " future bookings.");
    }

} catch (Exception $e) {
    dbRollback();
    error_log('Admin venue action error: ' . $e->getMessage());
    jsonError('Failed to process admin venue action.');
}
