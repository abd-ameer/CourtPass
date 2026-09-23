<?php
/**
 * All routes live here, grouped by module owner.
 * Pages return views. Routes under /api/ return JSON (for fetch()).
 *
 * Third argument = roles allowed: ['customer'], ['owner'], ['coach'], ['admin'], ['*'] = any logged-in user.
 *
 * @var Router $router
 */

// ---------- Shared & Public Routes ----------
$router->get('/', [HomeController::class, 'index']);
$router->get('/api/health', [SystemController::class, 'health']);

// Public Discovery & Info
$router->get('/venues', [PublicController::class, 'venues']);
$router->get('/venue-details', [PublicController::class, 'venueDetails']);
$router->get('/court-details', [PublicController::class, 'courtDetails']);
$router->get('/coaching', [PublicController::class, 'coaching']);
$router->get('/session-details', [PublicController::class, 'sessionDetails']);
$router->get('/coach-profile', [PublicController::class, 'coachProfile']);
$router->get('/resale', [PublicController::class, 'resale']);
$router->get('/reliability', [PublicController::class, 'reliability']);
$router->get('/help', [PublicController::class, 'help']);

// Auth & Onboarding
$router->get('/login', [AuthController::class, 'login']);
$router->post('/login', [AuthController::class, 'loginSubmit']);
$router->get('/demo-login', [AuthController::class, 'demoLogin']);
$router->get('/logout', [AuthController::class, 'logout']);
$router->get('/register', [AuthController::class, 'registerRole']);
$router->get('/register-role', [AuthController::class, 'registerRole']);
$router->get('/register-customer', [AuthController::class, 'registerCustomer']);
$router->get('/register-owner', [AuthController::class, 'registerOwner']);
$router->get('/register-coach', [AuthController::class, 'registerCoach']);

// ---------- Member A: Customer Dashboard & Bookings ----------
$router->get('/customer/dashboard', [CustomerController::class, 'dashboard']);
$router->get('/customer/venues', [CustomerController::class, 'venues']);
$router->get('/customer/venue-details', [CustomerController::class, 'venueDetails']);
$router->get('/customer/court-availability', [CustomerController::class, 'courtAvailability']);
$router->get('/customer/create-booking', [CustomerController::class, 'createBooking']);
$router->get('/customer/bookings', [CustomerController::class, 'bookings']);
$router->get('/customer/booking-details', [CustomerController::class, 'bookingDetails']);
$router->get('/customer/cancel-booking', [CustomerController::class, 'cancelBooking']);
$router->get('/customer/reschedule-booking', [CustomerController::class, 'rescheduleBooking']);
$router->get('/customer/resale', [CustomerController::class, 'resale']);
$router->get('/customer/my-resales', [CustomerController::class, 'myResales']);
$router->get('/customer/coaching', [CustomerController::class, 'coaching']);
$router->get('/customer/session-details', [CustomerController::class, 'sessionDetails']);
$router->get('/customer/my-sessions', [CustomerController::class, 'mySessions']);
$router->get('/customer/reliability', [CustomerController::class, 'reliability']);
$router->get('/customer/reviews', [CustomerController::class, 'reviews']);
$router->get('/customer/create-review', [CustomerController::class, 'createReview']);
$router->get('/customer/notifications', [CustomerController::class, 'notifications']);
$router->get('/customer/profile', [CustomerController::class, 'profile']);

// ---------- Member B: Venue Owner Dashboard & Operations ----------
$router->get('/owner/dashboard', [OwnerController::class, 'dashboard']);
$router->get('/owner/venues', [OwnerController::class, 'venues']);
$router->get('/owner/add-venue', [OwnerController::class, 'addVenue']);
$router->get('/owner/edit-venue', [OwnerController::class, 'editVenue']);
$router->get('/owner/delete-venue', [OwnerController::class, 'deleteVenue']);
$router->get('/owner/venue-details', [OwnerController::class, 'venueDetails']);
$router->get('/owner/courts', [OwnerController::class, 'courts']);
$router->get('/owner/edit-court', [OwnerController::class, 'editCourt']);
$router->get('/owner/operating-hours', [OwnerController::class, 'operatingHours']);
$router->get('/owner/slot-management', [OwnerController::class, 'slotManagement']);
$router->get('/owner/bookings', [OwnerController::class, 'bookings']);
$router->get('/owner/booking-details', [OwnerController::class, 'bookingDetails']);
$router->get('/owner/check-in', [OwnerController::class, 'checkIn']);
$router->get('/owner/flash-slots', [OwnerController::class, 'flashSlots']);
$router->get('/owner/announcements', [OwnerController::class, 'announcements']);
$router->get('/owner/coach-requests', [OwnerController::class, 'coachRequests']);
$router->get('/owner/customers', [OwnerController::class, 'customers']);
$router->get('/owner/revenue', [OwnerController::class, 'revenue']);
$router->get('/owner/utilisation', [OwnerController::class, 'utilisation']);
$router->get('/owner/notifications', [OwnerController::class, 'notifications']);
$router->get('/owner/profile', [OwnerController::class, 'profile']);

// ---------- Member C: Coach Dashboard & Clinic Operations ----------
$router->get('/coach/dashboard', [CoachController::class, 'dashboard']);
$router->get('/coach/sessions', [CoachController::class, 'sessions']);
$router->get('/coach/create-session', [CoachController::class, 'createSession']);
$router->get('/coach/edit-session', [CoachController::class, 'editSession']);
$router->get('/coach/cancel-session', [CoachController::class, 'cancelSession']);
$router->get('/coach/session-details', [CoachController::class, 'sessionDetails']);
$router->get('/coach/session-registrations', [CoachController::class, 'sessionRegistrations']);
$router->get('/coach/attendance', [CoachController::class, 'attendance']);
$router->get('/coach/venues', [CoachController::class, 'venues']);
$router->get('/coach/earnings', [CoachController::class, 'earnings']);
$router->get('/coach/reviews', [CoachController::class, 'reviews']);
$router->get('/coach/notifications', [CoachController::class, 'notifications']);
$router->get('/coach/profile', [CoachController::class, 'profile']);
$router->get('/coach/settings', [CoachController::class, 'settings']);

// ---------- Member D: Platform Admin & Moderation ----------
$router->get('/admin/dashboard', [AdminController::class, 'dashboard']);
$router->get('/admin/users', [AdminController::class, 'users']);
$router->get('/admin/user-details', [AdminController::class, 'userDetails']);
$router->get('/admin/venue-approvals', [AdminController::class, 'venueApprovals']);
$router->get('/admin/venue-details', [AdminController::class, 'venueDetails']);
$router->get('/admin/coach-verifications', [AdminController::class, 'coachVerifications']);
$router->get('/admin/disputes-no-show', [AdminController::class, 'disputesNoShow']);
$router->get('/admin/disputes-resale', [AdminController::class, 'disputesResale']);
$router->get('/admin/review-moderation', [AdminController::class, 'reviewModeration']);
$router->get('/admin/announcement-moderation', [AdminController::class, 'announcementModeration']);
$router->get('/admin/notifications', [AdminController::class, 'notifications']);
$router->get('/admin/settings', [AdminController::class, 'settings']);
