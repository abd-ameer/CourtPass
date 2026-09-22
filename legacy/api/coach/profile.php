<?php
/**
 * CourtPass — API: Coach Profile & NIC Upload Endpoints
 * Photo document check only — no NIC number stored to avoid PII exposure.
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/audit.php';
require_once __DIR__ . '/../../includes/notifications.php';

requireLogin(true);

$action = getVal('action', 'get');
$userId = currentUserId();
$role = currentUserRole();

if ($action === 'get') {
    $targetId = isset($_GET['coach_id']) ? (int)$_GET['coach_id'] : $userId;
    
    $profile = dbFetchOne(
        "SELECT cp.*, u.name, u.email, u.phone 
         FROM coach_profiles cp 
         JOIN users u ON cp.user_id = u.id 
         WHERE cp.user_id = ?",
        'i',
        [$targetId]
    );

    if (!$profile) {
        jsonError('Coach profile not found.', 404);
    }

    // Hide NIC path for non-admins
    if ($role !== 'admin' && $targetId !== $userId) {
        unset($profile['nic_document_path']);
    }

    jsonResponse(['success' => true, 'profile' => $profile]);
    exit;
}

if ($action === 'update_profile') {
    requireRole('coach', true);
    requireCsrf(true);

    $bio = trim($_POST['bio'] ?? '');
    $specializations = trim($_POST['specializations'] ?? '');

    dbQuery(
        "UPDATE coach_profiles SET bio = ?, specializations = ? WHERE user_id = ?",
        'ssi',
        [$bio, $specializations, $userId]
    );

    jsonSuccess('Profile updated successfully.');
    exit;
}

if ($action === 'upload_nic') {
    requireRole('coach', true);
    requireCsrf(true);

    if (!isset($_FILES['nic_file']) || $_FILES['nic_file']['error'] !== UPLOAD_ERR_OK) {
        jsonError('Please select a valid image file of your NIC document photo.');
    }

    $file = $_FILES['nic_file'];
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];

    if (!in_array($file['type'], $allowedTypes, true)) {
        jsonError('Only JPG, PNG, and WebP image files are allowed for NIC document submission.');
    }

    // Save photo artifact to uploads/coach_nic/
    if (!is_dir(COACH_NIC_UPLOAD_DIR)) {
        mkdir(COACH_NIC_UPLOAD_DIR, 0755, true);
    }

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = "nic_coach_{$userId}_" . time() . ".{$ext}";
    $targetPath = COACH_NIC_UPLOAD_DIR . $filename;
    $relativePath = 'uploads/coach_nic/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        jsonError('Failed to save NIC document image. Check folder permissions.');
    }

    // Update profile status to pending verification
    dbQuery(
        "UPDATE coach_profiles SET nic_document_path = ?, verification_status = 'pending' WHERE user_id = ?",
        'si',
        [$relativePath, $userId]
    );

    writeAuditLog($userId, 'coach_profile', $userId, 'nic_uploaded', ['path' => $relativePath]);

    jsonSuccess('NIC photo uploaded successfully! Submitted for platform admin verification.');
    exit;
}
