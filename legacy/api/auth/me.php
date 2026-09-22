<?php
/**
 * CourtPass — API: Me / Current User Endpoint
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/auth.php';

if (!isLoggedIn()) {
    jsonResponse(['logged_in' => false]);
}

$user = getCurrentUser();

// If customer, include reliability tier
$reliability = null;
if ($user && $user['role'] === 'customer') {
    require_once __DIR__ . '/../reliability/score.php';
    $reliability = getCustomerReliabilityInfo($user['id']);
}

jsonResponse([
    'logged_in' => true,
    'user' => $user,
    'reliability' => $reliability
]);
