<?php
/**
 * CourtPass — API: Login Endpoint
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonError('Method not allowed', 405);
}

requireCsrf(true);

$email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
$password = $_POST['password'] ?? '';

if (!$email || empty($password)) {
    jsonError('Please provide a valid email and password.');
}

$result = loginUser($email, $password);

if ($result['success']) {
    jsonSuccess($result['message'], ['user' => $result['user']]);
} else {
    jsonError($result['message'], 401);
}
