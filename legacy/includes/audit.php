<?php
/**
 * CourtPass — Audit Log
 * Immutable audit log — records every meaningful state change.
 * This table should never have UPDATE or DELETE operations.
 */

require_once __DIR__ . '/db.php';

/**
 * Write an immutable audit log entry.
 *
 * @param int|null $actorId   User ID performing the action (null for system actions)
 * @param string   $entityType Entity table name (e.g., 'booking', 'venue', 'user')
 * @param int      $entityId   ID of the entity being acted upon
 * @param string   $action     Action identifier (e.g., 'booking_created', 'status_changed')
 * @param array    $details    Additional context as key-value pairs
 */
function writeAuditLog(?int $actorId, string $entityType, int $entityId, string $action, array $details = []): void {
    try {
        $detailsJson = !empty($details) ? json_encode($details, JSON_UNESCAPED_UNICODE) : null;
        
        dbQuery(
            "INSERT INTO audit_log (actor_id, entity_type, entity_id, action, details) VALUES (?, ?, ?, ?, ?)",
            'isiss',
            [$actorId, $entityType, $entityId, $action, $detailsJson]
        );
    } catch (Exception $e) {
        // Audit log failures should never break the main flow.
        // Log the error but don't throw — the primary operation should succeed.
        error_log("CourtPass audit log write failed: {$e->getMessage()} | actor={$actorId} entity={$entityType}:{$entityId} action={$action}");
    }
}

/**
 * Get audit log entries for a specific entity.
 *
 * @param string $entityType
 * @param int    $entityId
 * @param int    $limit
 * @return array
 */
function getAuditLog(string $entityType, int $entityId, int $limit = 50): array {
    return dbFetchAll(
        "SELECT al.*, u.name AS actor_name 
         FROM audit_log al 
         LEFT JOIN users u ON al.actor_id = u.id 
         WHERE al.entity_type = ? AND al.entity_id = ? 
         ORDER BY al.created_at DESC 
         LIMIT ?",
        'sii',
        [$entityType, $entityId, $limit]
    );
}
