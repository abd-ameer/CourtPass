<?php
/**
 * CourtPass — API: Venues List Endpoint
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/auth.php';

$sport = getVal('sport');
$city = getVal('city');
$search = getVal('search');
$ownerOnly = getVal('owner_only');

$params = [];
$types = '';
$where = ["v.status = 'approved'"];

if ($ownerOnly && isLoggedIn() && currentUserRole() === 'owner') {
    $where = ["v.owner_id = ?"];
    $types .= 'i';
    $params[] = currentUserId();
}

if ($sport) {
    $where[] = "v.id IN (SELECT venue_id FROM courts WHERE sport_type = ? AND status = 'active')";
    $types .= 's';
    $params[] = $sport;
}

if ($city) {
    $where[] = "v.city = ?";
    $types .= 's';
    $params[] = $city;
}

if ($search) {
    $where[] = "(v.name LIKE ? OR v.description LIKE ? OR v.city LIKE ?)";
    $searchTerm = "%{$search}%";
    $types .= 'sss';
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
}

$whereClause = implode(' AND ', $where);

$sql = "SELECT v.*, u.name as owner_name, u.email as owner_email,
               (SELECT COUNT(*) FROM courts c WHERE c.venue_id = v.id AND c.status = 'active') as court_count,
               (SELECT ROUND(AVG(r.rating), 1) FROM reviews r WHERE r.venue_id = v.id AND r.status = 'active') as avg_rating,
               (SELECT COUNT(*) FROM reviews r WHERE r.venue_id = v.id AND r.status = 'active') as review_count
        FROM venues v
        JOIN users u ON v.owner_id = u.id
        WHERE {$whereClause}
        ORDER BY v.created_at DESC";

$venues = dbFetchAll($sql, $types, $params);

jsonResponse(['success' => true, 'venues' => $venues]);
