<?php
/**
 * CourtPass — API: Venue Creation Endpoint (Owner only)
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/audit.php';

requireLogin(true);
requireRole('owner', true);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonError('Method not allowed', 405);
}

requireCsrf(true);

$name = trim($_POST['name'] ?? '');
$description = trim($_POST['description'] ?? '');
$address = trim($_POST['address'] ?? '');
$city = trim($_POST['city'] ?? '');
$phone = sanitizePhone($_POST['phone'] ?? '');

if (empty($name) || empty($address) || empty($city)) {
    jsonError('Venue name, address, and city are required.');
}

$ownerId = currentUserId();

try {
    dbQuery(
        "INSERT INTO venues (owner_id, name, description, address, city, phone, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')",
        'isssss',
        [$ownerId, $name, $description, $address, $city, $phone]
    );

    $venueId = dbLastInsertId();

    writeAuditLog($ownerId, 'venue', $venueId, 'venue_registered', [
        'name' => $name,
        'city' => $city
    ]);

    jsonSuccess('Venue submitted successfully! It is now pending platform admin approval.', ['venue_id' => $venueId]);
} catch (Exception $e) {
    error_log('Venue creation error: ' . $e->getMessage());
    jsonError('Failed to create venue. Please try again.');
}
