# CourtPass Architecture Guide

CourtPass uses a **layered MVC** structure written in plain PHP (no framework).

## Request flow

```
Browser
  -> public/index.php        (front controller)
  -> Router                  (config/routes.php, role check)
  -> Controller              (validate input, call service)
  -> Service                 (business rules, transactions)
  -> Model                   (SQL with prepared statements)
  -> MySQL
  <- View (HTML page)  or  JSON (for fetch() calls to /api/...)
```

## Layer rules

| Layer | Does | Never |
|---|---|---|
| **Controller** | Read and validate input, check CSRF, call services, return a view or JSON | Write SQL, hold business rules |
| **Service** | Business rules, transactions (`Database::transaction`, `SELECT ... FOR UPDATE`), audit log, notifications, trigger-on-action checks | Output HTML or read `$_POST` directly |
| **Model** | One table each, CRUD with `mysqli` prepared statements | Business rules, sessions, output |
| **View** | Display data, escape everything with `e()` | Queries, logic beyond simple `if` / `foreach` |

Other rules:
- If your feature needs logic from another member's module, call **their service**. Do not copy it. Example: creating a coach session calls `BookingService`'s conflict check.
- No cron jobs. Automatic status changes (no-shows, expired flash slots, reliability) run when a user acts (login, booking attempt, page load).
- Every state change writes to the audit log.
- All times are Asia/Colombo.

## Naming

- One class per file, file name = class name, PascalCase.
- The suffix decides the folder (autoloaded, no `require` needed):

| Class | File |
|---|---|
| `BookingController` | `app/controllers/BookingController.php` |
| `BookingService` | `app/services/BookingService.php` |
| `BookingModel` | `app/models/BookingModel.php` |
| `Validator`, `Token` | `app/helpers/` |

- Views: `app/views/<module>/<page>.php`, e.g. `app/views/bookings/index.php`.
- API routes start with `/api/` and return JSON. Page routes return views.

## Adding a feature (example: list my bookings)

**1. Route** in your section of `config/routes.php`:
```php
$router->get('/bookings', [BookingController::class, 'index'], ['customer']);
$router->get('/api/bookings', [BookingController::class, 'list'], ['customer']);
```

**2. Model** `app/models/BookingModel.php`:
```php
class BookingModel extends Model
{
    public function forCustomer(int $customerId): array
    {
        return $this->select(
            'SELECT * FROM bookings WHERE customer_id = ? ORDER BY slot_start DESC',
            'i',
            [$customerId]
        );
    }
}
```

**3. Service** `app/services/BookingService.php`:
```php
class BookingService
{
    public function __construct(private BookingModel $bookings = new BookingModel())
    {
    }

    public function myBookings(int $customerId): array
    {
        return $this->bookings->forCustomer($customerId);
    }
}
```

**4. Controller** `app/controllers/BookingController.php`:
```php
class BookingController extends Controller
{
    public function index(): void
    {
        $bookings = (new BookingService())->myBookings(Auth::id());
        $this->view('bookings/index', ['title' => 'My Bookings', 'bookings' => $bookings]);
    }

    public function list(): void
    {
        $this->json(['bookings' => (new BookingService())->myBookings(Auth::id())]);
    }
}
```

**5. View** `app/views/bookings/index.php`:
```php
<h1>My Bookings</h1>
<?php foreach ($bookings as $b): ?>
    <p><?= e($b['slot_start']) ?> (<?= e($b['status']) ?>)</p>
<?php endforeach; ?>
```

## Forms and fetch()

- HTML forms: add `<?= csrf_field() ?>` inside the form and call `$this->verifyCsrf()` at the top of the POST action.
- JS: use `CourtPass.api('/api/bookings', { method: 'POST', data: {...} })` from `public/assets/js/app.js`. It sends the CSRF token for you.

## Auth helpers

- `Auth::login($user)`, `Auth::logout()`, `Auth::user()`, `Auth::id()`, `Auth::role()`, `Auth::hasRole('owner')`
- Roles: `customer`, `owner`, `coach`, `admin` (one per account).
- Protect routes with the third argument in `routes.php`: `['owner']`, `['coach', 'admin']`, or `['*']` for any logged-in user.

## Module ownership

| Member | Features | Typical files |
|---|---|---|
| A | Auth core, Customer sign-up, Booking Engine (slot grid, conflict check, state machine, cancellation), Reliability (no-show detection, score and tiers, cash-on-arrival eligibility, disputes) | `AuthController/Service`, `BookingController/Service/Model`, `ReliabilityService` |
| B | Owner sign-up, Venues, Courts and slot generation, slot blocking, Payments (PayHere Sandbox), Resale | `VenueController/Service/Model`, `CourtController/Service/Model`, `SlotService`, `PaymentService`, `ResaleService` |
| C | Coach Module (coach sign-up, venue approval, sessions, registration, Coaching page, attendance, coach dashboard, coach reviews) | `CoachController`, `CoachingController`, `SessionRegistrationController`, `CoachService`, `CoachSessionService`, `SessionRegistrationService` |
| D | Shared UI layout, Discovery, Check-in, venue Reviews, Announcements, Flash slots, Notifications, Audit log, Owner analytics, Customer intelligence, User management | `views/layouts`, `views/partials`, `DiscoveryController`, `CheckInService`, `ReviewService`, `FlashService`, `NotificationService`, `AuditService`, `AnalyticsService` |

Shared files (`app/core`, `config/routes.php`, `database/schema.sql`) need the team's agreement before changing.
