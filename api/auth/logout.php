<?php
/**
 * CourtPass — API: Logout Endpoint
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/auth.php';

logoutUser();

if (isset($_GET['redirect'])) {
    header('Location: ' . BASE_URL . '/login');
    exit;
}

jsonSuccess('Logged out successfully.');
