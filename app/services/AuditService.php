<?php
class AuditService
{
    /**
     * One row per state change. $event uses entity.action, e.g. booking.confirmed.
     * $actorId is null for automatic changes (hold expiry, no-show detection).
     */
    public function log(?int $actorId, string $event, string $entityType, int $entityId, ?string $oldStatus = null, ?string $newStatus = null, ?string $details = null): void
    {
        (new AuditLogModel())->create($actorId, $event, $entityType, $entityId, $oldStatus, $newStatus, $details);
    }
}
