<?php
/**
 * CourtPass — API: Register Endpoint
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonError('Method not allowed', 405);
}

requireCsrf(true);

$name = trim($_POST['name'] ?? '');
$email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
$phone = sanitizePhone($_POST['phone'] ?? '');
$password = $_POST['password'] ?? '';
$role = $_POST['role'] ?? 'customer';

if (empty($name) || strlen($name) < 2) {
    jsonError('Please enter a valid full name.');
}

if (!$email) {
    jsonError('Please enter a valid email address.');
}

if (empty($password) || strlen($password) < 6) {
    jsonError('Password must be at least 6 characters long.');
}

if (!in_array($role, ['customer', 'owner', 'coach'], true)) {
    jsonError('Invalid role specified.');
}

$result = registerUser($name, $email, $phone, $password, $role);

if ($result['success']) {
    // Auto-login after registration
    loginUser($email, $password);
    jsonSuccess('Registration successful!', ['user_id' => $result['user_id'], 'role' => $role]);
} else {
    jsonError($result['message']);
}
