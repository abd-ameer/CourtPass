<?php
/**
 * CourtPass — API: Browse Flash Deals Endpoint
 * Read-time auto-expiry check: expired slots no longer returned without background worker.
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/helpers.php';

// Auto-expire flash slots whose start time has passed (read-time check)
dbQuery(
    "UPDATE flash_slots 
     SET status = 'expired' 
     WHERE status = 'active' AND CONCAT(slot_date, ' ', slot_start) <= NOW()"
);

$deals = dbFetchAll(
    "SELECT fs.*, c.name as court_name, c.sport_type, v.name as venue_name, v.city
     FROM flash_slots fs
     JOIN courts c ON fs.court_id = c.id
     JOIN venues v ON fs.venue_id = v.id
     WHERE fs.status = 'active' 
       AND CONCAT(fs.slot_date, ' ', fs.slot_start) > NOW()
     ORDER BY fs.slot_date ASC, fs.slot_start ASC"
);

jsonResponse(['success' => true, 'flash_deals' => $deals]);
