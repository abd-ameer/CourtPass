# Pushing the UI Migration by Member

The branch `feature/v2-ui-migration` holds the refactored front end for the whole
team. Nothing here talks to the database yet: controllers render views and return
clearly marked `// TODO` placeholders where a service will go. The structure,
routes, guards, forms and CSRF are done, so login/sign-up and the interim CRUD can
be built straight on top.

Push to `main` in the order below. Each member owns the files in their section and
merges through a reviewed pull request, never a direct push to `main`.

## Shared foundation (push first, one PR, whole team agrees)

These are edited-in-common files that everything else depends on. Review together
and merge before any member's slice.

- `config/routes.php` (all sections; every route now has a role guard)
- `app/core/Router.php` (typed route params: `{id}` digits, `{slug}`, `{token}`)
- `app/helpers/functions.php` (`lkr()`, `format_datetime()`, `status_label()`, `status_badge()`)
- `app/views/layouts/main.php`, `app/views/layouts/dashboard.php`
- `app/views/partials/` (header, dashboard-header, sidebar, footer, modals, account-fields, venue-form-fields, court-hours-fields, venue-card, session-card)
- `public/assets/css/` (status-enum badges, layout shells, local `@font-face`)
- `public/assets/js/app.js` (fetch wrapper, `post()`, `confirmPost()`, `postWithReason()`, toast XSS fix)
- `public/assets/js/availability.js` (slot grid against `/api/courts/{id}/slots`)
- `public/assets/js/charts.js` (utilisation heatmap only)
- `public/assets/vendor/chartjs/`, `public/assets/vendor/figtree/` (local copies)
- `public/assets/img/` (logo moved here, venue placeholder)

## Member A — Accounts, Booking Engine, Reliability

Controllers: `AuthController`, `AccountController`, `BookingController`, `ReliabilityController`
Views: `auth/login.php`, `auth/register-role.php`, `auth/register-customer.php`,
`account/edit.php`, `customer/dashboard.php`, `customer/bookings.php`,
`customer/booking-details.php`, `customer/create-booking.php`,
`customer/cancel-booking.php`, `customer/reliability.php`,
`owner/bookings.php`, `owner/booking-details.php`,
`public/court-availability.php`, `admin/disputes-no-show.php`

## Member B — Venues, Courts, Payments, Resale

Controllers: `VenueController`, `CourtController`, `ResaleController`
Views: `auth/register-owner.php`, `owner/dashboard.php`, `owner/venues.php`,
`owner/venue-details.php`, `owner/add-venue.php`, `owner/edit-venue.php`,
`owner/deactivate-venue.php`, `owner/courts.php`, `owner/edit-court.php`,
`owner/operating-hours.php`, `owner/slot-management.php`,
`customer/released-bookings.php`, `admin/dashboard.php`,
`admin/venue-approvals.php`, `admin/venue-details.php`, `admin/disputes-resale.php`

## Member C — Coach Module

Controllers: `CoachController`, `CoachSessionController`, `SessionRegistrationController`
Views: `auth/register-coach.php`, `coach/dashboard.php`, `coach/profile.php`,
`coach/venues.php`, `coach/sessions.php`, `coach/create-session.php`,
`coach/edit-session.php`, `coach/session-details.php`,
`coach/session-registrations.php`, `coach/attendance.php`,
`coach/cancel-session.php`, `coach/earnings.php`, `coach/reviews.php`,
`coach/session-details.php`, `customer/my-sessions.php`,
`owner/coach-requests.php`, `admin/coach-verifications.php`,
`public/coaching.php`, `public/coach-profile.php`, `public/session-details.php`

## Member D — Discovery, Engagement, Insights

Controllers: `DiscoveryController`, `ReviewController`, `CheckInController`,
`AnnouncementController`, `FlashSlotController`, `NotificationController`,
`AnalyticsController`, `AdminUserController`
Views: `home/index.php`, `public/venues.php`, `public/venue-details.php`,
`public/help.php`, `customer/reviews.php`, `owner/check-in.php`,
`owner/announcements.php`, `owner/flash-slots.php`, `owner/revenue.php`,
`owner/utilisation.php`, `owner/customers.php`, `admin/users.php`,
`admin/user-details.php`, `admin/review-moderation.php`,
`admin/announcement-moderation.php`

## Shared between C and D

`customer/create-review.php` renders for both venue reviews (D) and coach reviews (C).
Whoever pushes it first owns the file; the other reviews the PR.

## Notes carried into the build phase

- Class names for models and services are not decided. Propose and get agreement
  before adding one (codebase map, section on naming).
- `coach_venue_approvals` has no sport column, but the coach spec mentions
  requesting approval per sport type. Resolve with a schema decision before
  building coach venue approval.
- Table and column names are fixed by `database/schema.sql`. Use them exactly.
