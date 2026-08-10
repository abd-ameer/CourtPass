# CourtPass — Build Prompt for Antigravity

Copy everything below the line into Antigravity as the task/system prompt.

---

## Project

Build **CourtPass**, a web-based sports venue booking and community platform for the Sri Lankan indoor recreational sports market (futsal, badminton, pickleball, squash, billiards, carrom, table tennis). It connects venue owners with customers for slot-based court booking, and adds a reliability/trust layer, a resale marketplace, flash deals, a coach-led session module, and a community layer (Sports Diary, reviews, leaderboards).

This is an academic capstone project (UCSC, Group 40) and **must respect strict technology constraints** — do not substitute frameworks or libraries even if they would be "easier."

## Hard Technology Constraints (do not deviate)

- **Frontend:** HTML, CSS, vanilla JavaScript only. No React/Vue/Angular. Use the native `fetch()` API to call the app's own PHP endpoints (like an AJAX form submit).
- **Backend:** PHP, **no framework** (no Laravel, Symfony, Slim, etc.). Plain PHP files/routing, session-based auth via native PHP sessions.
- **Database:** MySQL, raw SQL via `mysqli`. No ORM, no query builder.
- **Auth:** native `password_hash()` / `password_verify()`. No external auth library.
- **Config/secrets:** a git-ignored `config.php` per environment holding DB credentials and PayHere sandbox keys. No `.env` libraries.
- **Only two third-party additions are permitted in the deployed app:** Chart.js (owner dashboard charts only) and the **PayHere Sandbox** payment gateway integration.
- **Dev tooling (not part of the runtime app, so these are fine to use during build):** XAMPP/Apache/MySQL/phpMyAdmin locally, Git/GitHub, Postman for API testing, PHPUnit for unit tests (booking conflict engine, reliability scoring), draw.io for diagrams.
- Do not add npm build pipelines, bundlers, CSS frameworks (Tailwind, Bootstrap is a judgment call — default to none unless asked), or any other JS/PHP package unless explicitly approved.
- All timestamps/business logic assume **Asia/Colombo (UTC+5:30)**.

## Actors

- **Guest** — unauthenticated visitor; browse venues/courts/slots read-only; attempting to book redirects to register/login.
- **Customer** — books courts, manages/cancels/resells bookings, tracks reliability, has a Sports Diary, registers for coach sessions.
- **Venue Owner** — registers venues (pending admin approval), manages courts/operating hours, confirms/rejects/cancels bookings, runs flash deals, posts announcements, views analytics dashboard and customer intelligence profiles, approves/declines coaches at their venue.
- **Coach** — independent operator; registers separately from customers, requests venue approval, creates public/private sessions, manages a dashboard, builds a reviewed public profile. Coaches **cannot** book courts as customers — they are a distinct role, players only.
- **Platform Admin** — approves/rejects venues, deactivates venues/users/coaches, verifies coach identity, resolves no-show and resale disputes, removes reviews/announcements.
- **Automated System** — background/trigger-on-action logic: no-show detection, flash slot expiry, reliability recalculation, tier updates, cash-on-arrival unlocking.

## Core Domain Model (design the ERD around this)

Design a normalized MySQL schema. At minimum you'll need entities for: users (with role: customer/owner/coach/admin), venues, courts, operating_hours, slots (or generate on the fly from operating hours — decide and document), bookings, payments, resale_listings, flash_slots, reviews, announcements, reliability_scores/history, audit_log, notifications, coach_profiles, sessions (coach sessions — name it `coach_sessions` to avoid clashing with PHP sessions), session_registrations, coach_venue_approvals.

Lock the schema in an early "walking skeleton" spike before parallel development starts (see Suggested Build Order below) — the reliability, booking, payment, and resale modules all share this schema and a design flaw found late is expensive to fix.

## Booking State Machine (implement exactly, with an immutable audit log entry on every transition)

```
Pending → Confirmed → Completed
                    → Cancelled
                    → No-Show
```

- Slots are fixed at **one hour** only (no variable duration).
- A **database-level conflict check** must run before any booking insert (use a transaction + unique constraint or `SELECT ... FOR UPDATE`, not just an application-level check, to prevent double-booking under concurrency).
- No-show detection is **trigger-on-action**: evaluated on the customer's next login or booking attempt, not via a cron/real-time job (real-time/WebSockets are explicitly out of scope). Admin can manually clear a no-show flag given evidence.
- Owner actions (confirm/reject/cancel) require mandatory reasons for reject/cancel, stored and auditable.

### Cancellation policy — implement precisely

**Online-paid bookings:**
| Time before slot | Outcome |
|---|---|
| > 48 hrs | Full refund (simulated via PayHere Sandbox) |
| 12–48 hrs | 50% refund (simulated) **or** customer may list for resale instead |
| < 12 hrs, or listed for resale and unsold by slot time | No refund |

**Cash-on-arrival bookings** (reliability penalty instead of money, since no payment was taken):
| Time before slot | Classification | Effect |
|---|---|---|
| > 48 hrs | Responsible | No penalty |
| 12–48 hrs | Moderate | Minor penalty |
| < 12 hrs | Irresponsible | Larger reliability penalty |

## Reliability & Trust System

- Recalculated **trigger-on-action** (login or booking attempt), not continuously.
- Tiers:

| Tier | Condition | Payment eligibility |
|---|---|---|
| New Member | Fewer than 5 completed bookings | Online only |
| Restricted | Reliability score below 70% | Online only |
| Standard+ | Score ≥ 70% (and 5+ completed bookings) | Choice of online or cash-on-arrival |

- New Member accounts display "New Member," never a numeric percentage.
- Score inputs: completed bookings, no-shows, and classified cancellations (see table above).
- A repeated pattern of irresponsible cancellations/no-shows must be able to **downgrade** a Standard+ customer back below the eligibility threshold, losing cash-on-arrival privileges.
- Admin can clear disputed no-show penalties; clearing must trigger an automatic recalculation.

## Resale Marketplace

- Only **confirmed** bookings can be listed.
- Listing price capped at **90% of the original booking price**, enforced **server-side** (never trust client-submitted price).
- Buyer pays via PayHere Sandbox; on successful sandbox payment, ownership transfers atomically.
- Unclaimed listings auto-expire at slot start time; booking reverts to the original holder.
- Resolve the open design question from the coach-module notes as follows unless the supervisor says otherwise: **cash-on-arrival bookings must be converted to online payment before they can be listed for resale**, and this conversion is presented to the customer as an explicit choice, not a silent/forced gate. Implement resale listing eligibility as: booking is online-paid (directly or via this conversion) AND confirmed AND not already listed.
- Any customer can **revoke** their own unsold listing at any time with no penalty; the booking simply reverts to confirmed.
- Owners cannot reject a booking that resulted from a resale purchase, but the owner-facing customer intelligence view should show a "restricted-tier" flag on the buyer if applicable, since payment is already settled regardless of the buyer's tier.

## Flash Slots

Owner marks an unbooked *upcoming* slot as a discounted Flash Deal. It appears in an "Available Now" listing and auto-expires (no longer bookable) once the slot's start time passes, without needing a live background worker — check expiry at read time.

## Community Layer

- **Sports Diary** (customer-facing): total sessions played, venues visited, sport-type breakdown, most active month, member-since date — all derived/aggregated from booking + check-in data, not separately entered.
- **Most Active This Month**: per-venue leaderboard of top 5 customers by verified attendance.
- **Reviews:** customer can review only after a verified check-in event, within 7 days of that check-in. One public owner response per review (second attempt rejected server-side). Admin can remove reviews violating policy.
- **Announcements:** owner posts Operational or Promotional announcements per venue.

## Owner Dashboard

- Booking history, revenue tracking, **court utilisation heatmap** (Chart.js — this is the one sanctioned use of it), customer intelligence profile (reliability score, venue-specific history, no-show count, last visit) shown when reviewing a booking request.

## Payments (PayHere Sandbox)

- Redirect to PayHere Sandbox for any online payment (bookings, resale purchases, coach session registrations).
- Verify sandbox payment confirmation server-side before marking a reservation/registration Confirmed/paid.
- Record payment status per transaction; handle failed/cancelled payment callbacks gracefully and notify the customer.
- No real card data is ever stored by the app — it's fully delegated to PayHere.

## Admin Panel

Venue approval/rejection (with mandatory reason), forced venue deactivation (cascades to auto-cancel all future bookings at that venue), user/owner/coach account deactivation, no-show dispute review and clearing, resale dispute resolution, review/announcement removal, coach identity verification flag.

---

## Coach Module (build as a distinct module, integrated with — not duplicating — the core booking engine)

### Roles & flow
- Coach registers via a **separate form** from customer registration (role = coach).
- Coach identity verification: coach uploads a **photo of their NIC document** for admin review. **Do not store the NIC number itself** — store only the verification artifact/flag needed to clear the coach, to avoid the legal/PII exposure the design notes explicitly flag. Confirm this handling with whoever owns data-retention decisions before finalizing storage — document the assumption clearly in code comments and the system docs either way.
- Coach requests approval to operate at specific venues; venue owner approves/declines per request; owner can see all currently active coaches at their venue.
- **Coaches cannot book courts as players.** They are a separate actor with no customer-booking capability in this system.

### Sessions
- Coach creates sessions with a capacity; supports **public** (listed, discoverable by all customers) and **private** (only reachable via a random, unguessable shareable token/link) sessions.
- Session creation must go through the **same conflict-check integration point** as court bookings — a session occupies a court/time slot, so it must be conflict-checked against existing bookings/sessions on that court.
- Registration count is tracked against capacity; the session auto-closes to new registrations when full.
- Coach can cancel a session: this must **trigger a full refund to every registered student** and update the underlying court's block/availability status.

### Coach dashboard (build exactly this — no more, no less, unless asked)
- **Session management:** upcoming sessions with registration-count/capacity, cancel-from-here action, per-session shareable link.
- **Earnings summary:** this month's revenue, all-time revenue, revenue split by session type (group vs private). No charts required here.
- **Student overview:** total unique students coached, returning vs first-time this month, "regulars" (3+ sessions attended).
- **Session history:** past sessions with attendance counts and the coach's own cancellation rate.
- **Review summary:** overall average rating, average rating split by Parent vs Player tag, recent reviews.

### Reviews for coaches
- Review submission requires attendance verification (gate on a confirmed/attended registration).
- Reviewer must self-select a **Parent** or **Player** tag at submission time, enforced/stored server-side.
- Aggregation: overall average, average-by-tag, recent reviews list with tag labels.
- One public coach response per review; a second response attempt is rejected server-side.
- Admin can remove policy-violating reviews.

### Notifications
Every event below must write a row to a `notifications` table at the moment it occurs, inserted directly inside the relevant service method (not via a separate batch job):

| Event | Recipient(s) |
|---|---|
| Coach approved at a venue | Coach |
| Coach declined at a venue | Coach (with reason) |
| New registration for a session | Coach |
| Student cancels a registration | Coach |
| Session cancelled by coach | All registered students |
| Session reaches full capacity | Coach |
| Review posted on coach's profile | Coach |
| Coach identity verification completed | Coach |

### Open decisions to flag back to me, don't silently assume

The design notes leave two things unresolved — implement the most conservative/simple option below as a default, but call it out explicitly in your response so it can be revisited:
1. **Attendance/reliability for coach sessions:** default to tracking simple attendance (attended / no-show) per registration for the "regulars" and student-overview stats, but **do not** feed coach-session attendance into the customer's court-booking reliability score — keep the two systems separate unless told otherwise.
2. Confirm the resale/cash-on-arrival conversion approach above matches intent before treating it as final.

---

## Quality Attributes (design and code to these explicitly)

- **Data integrity:** DB-level conflict checks prevent double-booking; server-side enforcement of the 90% resale cap on every submission (never trust the client).
- **Accountability:** an immutable audit-log table records every meaningful state change with actor ID, timestamp, and event type.
- **Security:** hashed passwords, secure session handling, input validation/sanitization everywhere (prepared statements only — no raw string-concatenated SQL), payment details never touch the app's own storage.
- **Usability:** simple, low-friction HTML/CSS UI usable by non-technical venue operators on a basic browser/smartphone — no heavy JS UI framework, no required app install.
- **Maintainability:** agree the schema and status enums up front (schema-first) and keep enum values consistent across every module; keep a branch-per-feature Git workflow.
- **Reliability:** validation, error handling, and audit logging around every state-changing operation, especially the booking and payment flows.

## Explicitly Out of Scope — do not build these

Real-time/WebSocket updates, a mobile app, GPS/map-based search, in-app messaging/chat, automated SMS/email notifications (in-app `notifications` table only), dynamic/surge pricing, recurring bookings, NIC number storage or verification beyond the document-photo check described above, waitlist/queued promotion for full slots.

## Suggested Build Order

1. **Walking skeleton first:** one booking flowing end-to-end through creation → status change → audit log → reliability stub, to prove the schema and module boundaries before building features on top of it.
2. Core entity management: auth/roles, venue registration + admin approval, court management, operating-hours-driven slot generation.
3. Booking engine: full state machine, DB-level conflict checking, PayHere Sandbox integration, cancellation policy, reliability v1 (read-only).
4. Reliability-gated features: tier-based cash-on-arrival eligibility, resale marketplace with server-enforced price cap, flash slots, no-show detection.
5. Coach module: registration + venue approval, session management with shared conflict-check integration, registration/payment, coach profile + dashboard, reviews with tag enforcement, notifications table wiring.
6. Community layer: Sports Diary aggregation, Most Active This Month, announcements, verified reviews for venues.
7. Owner analytics dashboard (Chart.js heatmap) and admin panel.
8. Polish: UI consistency, edge cases, PHPUnit coverage for the booking conflict engine and reliability scoring, final docs.

## Deliverables to produce alongside the code

- Runnable LAMP-stack app (XAMPP-compatible locally).
- MySQL schema + seed data scripts.
- ERD, use-case diagrams, and data-flow diagrams (draw.io-style, can be markdown/mermaid if diagram tooling isn't available).
- Short user docs for the three human roles that matter operationally (Owner, Customer, Coach) plus an admin guide.

---

**When you start, first propose the ERD and the exact booking/session state-machine as code/schema for review before writing feature code**, since several modules share these structures and a mismatch found late is costly to unwind.
