# CourtPass — Entity Relationship Diagram (ERD)

```mermaid
erDiagram
    USERS ||--o{ VENUES : "owns"
    USERS ||--o{ BOOKINGS : "books"
    USERS ||--o| RELIABILITY_SCORES : "has"
    USERS ||--o| COACH_PROFILES : "has"
    VENUES ||--o{ COURTS : "contains"
    COURTS ||--o{ OPERATING_HOURS : "has"
    COURTS ||--o{ BOOKINGS : "booked_for"
    BOOKINGS ||--o{ PAYMENTS : "paid_via"
    BOOKINGS ||--o| RESALE_LISTINGS : "listed_in"
    VENUES ||--o{ FLASH_SLOTS : "has"
    VENUES ||--o{ REVIEWS : "reviewed"
    VENUES ||--o{ ANNOUNCEMENTS : "has"
    COACH_PROFILES ||--o{ COACH_VENUE_APPROVALS : "requests"
    VENUES ||--o{ COACH_VENUE_APPROVALS : "approves"
    COACH_PROFILES ||--o{ COACH_SESSIONS : "creates"
    COURTS ||--o{ COACH_SESSIONS : "held_at"
    COACH_SESSIONS ||--o{ SESSION_REGISTRATIONS : "has"
    COACH_PROFILES ||--o{ COACH_REVIEWS : "receives"
```

## Schema Overview
- 17 Normalized Tables
- Hard DB constraints (UNIQUE constraint on `(court_id, slot_date, slot_start, status)` to prevent double-booking)
- Immutable Audit Log table for tracking every state transition
