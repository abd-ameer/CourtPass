<?php
/** Insert only: database triggers refuse UPDATE and DELETE on audit_log. */
class AuditLogModel extends Model
{
    public function create(?int $actorId, string $eventType, string $entityType, int $entityId, ?string $oldStatus, ?string $newStatus, ?string $details): int
    {
        return $this->insert(
            'INSERT INTO audit_log (actor_id, event_type, entity_type, entity_id, old_status, new_status, details)
             VALUES (?, ?, ?, ?, ?, ?, ?)',
            'ississs',
            [$actorId, $eventType, $entityType, $entityId, $oldStatus, $newStatus, $details]
        );
    }
}
