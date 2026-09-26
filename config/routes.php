<?php
/**
 * All routes, one section per member. Add routes only inside your own section.
 * Pages return views. Routes under /api/ return JSON.
 * Third argument = roles allowed: ['customer'], ['owner'], ['coach'], ['admin'], ['*'] = any logged-in user.
 * Resource ids are path segments ({id} matches digits only). Query strings are for filters.
 * PUT and DELETE forms send POST with <input type="hidden" name="_method" value="PUT">.
 *
 * @var Router $router
 */

// ---------- Shared ----------
$router->get('/api/health', [SystemController::class, 'health']);

// ---------- Member A: Accounts, Booking Engine, Reliability ----------
$router->get('/login', [AuthController::class, 'login']);
$router->post('/login', [AuthController::class, 'authenticate']);
$router->post('/logout', [AuthController::class, 'logout'], ['*']);
$router->get('/register', [AuthController::class, 'chooseRole']);
$router->get('/register/customer', [AuthController::class, 'customerForm']);
$router->post('/register/customer', [AuthController::class, 'registerCustomer']);

$router->get('/account', [AccountController::class, 'edit'], ['*']);
$router->put('/account', [AccountController::class, 'update'], ['*']);
$router->put('/account/password', [AccountController::class, 'updatePassword'], ['*']);

$router->get('/courts/{id}', [BookingController::class, 'availability']);
$router->get('/api/courts/{id}/slots', [BookingController::class, 'slots']);

$router->get('/customer/dashboard', [BookingController::class, 'dashboard'], ['customer']);
$router->get('/customer/bookings', [BookingController::class, 'index'], ['customer']);
$router->get('/customer/bookings/create', [BookingController::class, 'create'], ['customer']);
$router->post('/customer/bookings', [BookingController::class, 'store'], ['customer']);
$router->get('/customer/bookings/{id}', [BookingController::class, 'show'], ['customer']);
$router->get('/customer/bookings/{id}/cancel', [BookingController::class, 'confirmCancel'], ['customer']);
$router->post('/customer/bookings/{id}/cancel', [BookingController::class, 'cancel'], ['customer']);

$router->get('/owner/bookings', [BookingController::class, 'ownerIndex'], ['owner']);
$router->get('/owner/bookings/{id}', [BookingController::class, 'ownerShow'], ['owner']);
$router->post('/owner/bookings/{id}/confirm', [BookingController::class, 'confirm'], ['owner']);
$router->post('/owner/bookings/{id}/reject', [BookingController::class, 'reject'], ['owner']);
$router->post('/owner/bookings/{id}/cancel', [BookingController::class, 'ownerCancel'], ['owner']);

$router->get('/customer/reliability', [ReliabilityController::class, 'show'], ['customer']);
$router->post('/customer/disputes', [ReliabilityController::class, 'storeDispute'], ['customer']);
$router->get('/admin/disputes/no-show', [ReliabilityController::class, 'adminIndex'], ['admin']);
$router->post('/admin/disputes/no-show/{id}/resolve', [ReliabilityController::class, 'resolve'], ['admin']);

// ---------- Member B: Venues, Courts, Payments, Resale ----------
$router->get('/register/owner', [VenueController::class, 'ownerForm']);
$router->post('/register/owner', [VenueController::class, 'registerOwner']);

$router->get('/owner/dashboard', [VenueController::class, 'dashboard'], ['owner']);
$router->get('/owner/venues', [VenueController::class, 'index'], ['owner']);
$router->get('/owner/venues/create', [VenueController::class, 'create'], ['owner']);
$router->post('/owner/venues', [VenueController::class, 'store'], ['owner']);
$router->get('/owner/venues/{id}', [VenueController::class, 'show'], ['owner']);
$router->get('/owner/venues/{id}/edit', [VenueController::class, 'edit'], ['owner']);
$router->put('/owner/venues/{id}', [VenueController::class, 'update'], ['owner']);
$router->get('/owner/venues/{id}/deactivate', [VenueController::class, 'confirmDeactivate'], ['owner']);
$router->post('/owner/venues/{id}/deactivate', [VenueController::class, 'deactivate'], ['owner']);
$router->post('/owner/venues/{id}/activate', [VenueController::class, 'activate'], ['owner']);

$router->get('/admin/dashboard', [VenueController::class, 'adminDashboard'], ['admin']);
$router->get('/admin/venues', [VenueController::class, 'adminIndex'], ['admin']);
$router->get('/admin/venues/{id}', [VenueController::class, 'adminShow'], ['admin']);
$router->post('/admin/venues/{id}/approve', [VenueController::class, 'approve'], ['admin']);
$router->post('/admin/venues/{id}/reject', [VenueController::class, 'reject'], ['admin']);
$router->post('/admin/venues/{id}/deactivate', [VenueController::class, 'forceDeactivate'], ['admin']);

$router->get('/owner/courts', [CourtController::class, 'index'], ['owner']);
$router->get('/owner/courts/create', [CourtController::class, 'create'], ['owner']);
$router->post('/owner/courts', [CourtController::class, 'store'], ['owner']);
$router->get('/owner/courts/{id}/edit', [CourtController::class, 'edit'], ['owner']);
$router->put('/owner/courts/{id}', [CourtController::class, 'update'], ['owner']);
$router->get('/owner/courts/{id}/hours', [CourtController::class, 'hours'], ['owner']);
$router->put('/owner/courts/{id}/hours', [CourtController::class, 'updateHours'], ['owner']);
$router->get('/owner/slots', [CourtController::class, 'slots'], ['owner']);
$router->post('/owner/blocks', [CourtController::class, 'block'], ['owner']);
$router->delete('/owner/blocks/{id}', [CourtController::class, 'unblock'], ['owner']);

$router->get('/customer/releases', [ResaleController::class, 'index'], ['customer']);
$router->post('/customer/bookings/{id}/release', [ResaleController::class, 'release'], ['customer']);
$router->post('/customer/bookings/{id}/take-back', [ResaleController::class, 'takeBack'], ['customer']);
$router->get('/admin/disputes/resale', [ResaleController::class, 'adminIndex'], ['admin']);
$router->post('/admin/disputes/resale/{id}/resolve', [ResaleController::class, 'resolve'], ['admin']);

// ---------- Member C: Coach Module ----------
$router->get('/register/coach', [CoachController::class, 'coachForm']);
$router->post('/register/coach', [CoachController::class, 'registerCoach']);

$router->get('/coaching', [CoachController::class, 'coaching']);
$router->get('/coaches/{id}', [CoachController::class, 'publicProfile']);

$router->get('/coach/dashboard', [CoachController::class, 'dashboard'], ['coach']);
$router->get('/coach/profile', [CoachController::class, 'editProfile'], ['coach']);
$router->put('/coach/profile', [CoachController::class, 'updateProfile'], ['coach']);
$router->get('/coach/earnings', [CoachController::class, 'earnings'], ['coach']);
$router->get('/coach/reviews', [CoachController::class, 'reviews'], ['coach']);
$router->post('/coach/reviews/{id}/response', [CoachController::class, 'respond'], ['coach']);
$router->get('/coach/venues', [CoachController::class, 'venues'], ['coach']);
$router->post('/coach/venue-requests', [CoachController::class, 'requestVenue'], ['coach']);

$router->get('/owner/coach-requests', [CoachController::class, 'ownerRequests'], ['owner']);
$router->post('/owner/coach-requests/{id}/approve', [CoachController::class, 'approveRequest'], ['owner']);
$router->post('/owner/coach-requests/{id}/decline', [CoachController::class, 'declineRequest'], ['owner']);
$router->post('/owner/coach-requests/{id}/revoke', [CoachController::class, 'revokeApproval'], ['owner']);

$router->get('/admin/coaches', [CoachController::class, 'adminIndex'], ['admin']);
$router->post('/admin/coaches/{id}/verify', [CoachController::class, 'verify'], ['admin']);

$router->get('/coach/sessions', [CoachSessionController::class, 'index'], ['coach']);
$router->get('/coach/sessions/create', [CoachSessionController::class, 'create'], ['coach']);
$router->post('/coach/sessions', [CoachSessionController::class, 'store'], ['coach']);
$router->get('/coach/sessions/{id}', [CoachSessionController::class, 'show'], ['coach']);
$router->get('/coach/sessions/{id}/edit', [CoachSessionController::class, 'edit'], ['coach']);
$router->put('/coach/sessions/{id}', [CoachSessionController::class, 'update'], ['coach']);
$router->get('/coach/sessions/{id}/cancel', [CoachSessionController::class, 'confirmCancel'], ['coach']);
$router->post('/coach/sessions/{id}/cancel', [CoachSessionController::class, 'cancel'], ['coach']);
$router->get('/coach/sessions/{id}/registrations', [CoachSessionController::class, 'registrations'], ['coach']);
$router->get('/coach/sessions/{id}/attendance', [CoachSessionController::class, 'attendance'], ['coach']);
$router->put('/coach/sessions/{id}/attendance', [CoachSessionController::class, 'saveAttendance'], ['coach']);

$router->get('/sessions/{id}', [SessionRegistrationController::class, 'show']);
$router->get('/sessions/private/{token}', [SessionRegistrationController::class, 'showPrivate']);
$router->post('/sessions/{id}/register', [SessionRegistrationController::class, 'store'], ['customer']);
$router->get('/customer/sessions', [SessionRegistrationController::class, 'index'], ['customer']);
$router->post('/customer/registrations/{id}/cancel', [SessionRegistrationController::class, 'cancel'], ['customer']);
$router->get('/customer/registrations/{id}/review', [SessionRegistrationController::class, 'createReview'], ['customer']);
$router->post('/customer/registrations/{id}/review', [SessionRegistrationController::class, 'storeReview'], ['customer']);

// ---------- Member D: Discovery, Engagement, Insights ----------
$router->get('/', [DiscoveryController::class, 'home']);
$router->get('/venues', [DiscoveryController::class, 'venues']);
$router->get('/venue/{slug}', [DiscoveryController::class, 'venue']);
$router->get('/help', [DiscoveryController::class, 'help']);

$router->get('/customer/reviews', [ReviewController::class, 'index'], ['customer']);
$router->get('/customer/bookings/{id}/review', [ReviewController::class, 'create'], ['customer']);
$router->post('/customer/bookings/{id}/review', [ReviewController::class, 'store'], ['customer']);
$router->get('/customer/reviews/{id}/edit', [ReviewController::class, 'edit'], ['customer']);
$router->put('/customer/reviews/{id}', [ReviewController::class, 'update'], ['customer']);
$router->delete('/customer/reviews/{id}', [ReviewController::class, 'destroy'], ['customer']);
$router->get('/admin/reviews', [ReviewController::class, 'moderation'], ['admin']);
$router->post('/admin/reviews/{id}/remove', [ReviewController::class, 'remove'], ['admin']);
$router->post('/admin/reviews/{id}/dismiss-flag', [ReviewController::class, 'dismissFlag'], ['admin']);
$router->get('/owner/reviews', [ReviewController::class, 'ownerIndex'], ['owner']);
$router->post('/owner/reviews/{id}/response', [ReviewController::class, 'respond'], ['owner']);
$router->post('/owner/reviews/{id}/flag', [ReviewController::class, 'flag'], ['owner']);

$router->get('/owner/check-in', [CheckInController::class, 'index'], ['owner']);
$router->post('/owner/bookings/{id}/check-in', [CheckInController::class, 'store'], ['owner']);

$router->get('/owner/announcements', [AnnouncementController::class, 'index'], ['owner']);
$router->post('/owner/announcements', [AnnouncementController::class, 'store'], ['owner']);
$router->get('/admin/announcements', [AnnouncementController::class, 'moderation'], ['admin']);
$router->post('/admin/announcements/{id}/remove', [AnnouncementController::class, 'remove'], ['admin']);

$router->get('/owner/flash-slots', [FlashSlotController::class, 'index'], ['owner']);
$router->post('/owner/flash-slots', [FlashSlotController::class, 'store'], ['owner']);

$router->get('/notifications', [NotificationController::class, 'index'], ['*']);
$router->post('/notifications/read', [NotificationController::class, 'markAllRead'], ['*']);

$router->get('/owner/revenue', [AnalyticsController::class, 'revenue'], ['owner']);
$router->get('/owner/utilisation', [AnalyticsController::class, 'utilisation'], ['owner']);
$router->get('/owner/customers', [AnalyticsController::class, 'customers'], ['owner']);

$router->get('/admin/users', [AdminUserController::class, 'index'], ['admin']);
$router->get('/admin/users/{id}', [AdminUserController::class, 'show'], ['admin']);
$router->post('/admin/users/{id}/deactivate', [AdminUserController::class, 'deactivate'], ['admin']);
