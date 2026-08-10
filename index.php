<?php
/**
 * CourtPass — Front Controller
 * Routes all requests to the appropriate page or API endpoint.
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/auth.php';

// Initialize session for every request
initSession();

// Get the route from the URL
$route = isset($_GET['route']) ? trim($_GET['route'], '/') : '';

// ─── API Routes ─────────────────────────────────────────────
// API endpoints return JSON and are called via fetch()
if (str_starts_with($route, 'api/')) {
    header('Content-Type: application/json; charset=utf-8');
    
    $apiRoute = substr($route, 4); // Remove 'api/' prefix
    
    $apiRoutes = [
        // Auth
        'auth/login'           => 'api/auth/login.php',
        'auth/register'        => 'api/auth/register.php',
        'auth/logout'          => 'api/auth/logout.php',
        'auth/me'              => 'api/auth/me.php',
        
        // Venues
        'venues/list'          => 'api/venues/list.php',
        'venues/detail'        => 'api/venues/detail.php',
        'venues/create'        => 'api/venues/create.php',
        'venues/update'        => 'api/venues/update.php',
        'venues/courts'        => 'api/venues/courts.php',
        'venues/hours'         => 'api/venues/hours.php',
        
        // Courts
        'courts/create'        => 'api/courts/create.php',
        'courts/update'        => 'api/courts/update.php',
        'courts/slots'         => 'api/courts/slots.php',
        
        // Bookings
        'bookings/create'      => 'api/bookings/create.php',
        'bookings/list'        => 'api/bookings/list.php',
        'bookings/detail'      => 'api/bookings/detail.php',
        'bookings/confirm'     => 'api/bookings/confirm.php',
        'bookings/reject'      => 'api/bookings/reject.php',
        'bookings/cancel'      => 'api/bookings/cancel.php',
        'bookings/complete'    => 'api/bookings/complete.php',
        
        // Payments
        'payments/initiate'    => 'api/payments/initiate.php',
        'payments/notify'      => 'api/payments/notify.php',
        'payments/return'      => 'api/payments/return.php',
        'payments/cancel'      => 'api/payments/cancel.php',
        
        // Resale
        'resale/list'          => 'api/resale/list.php',
        'resale/create'        => 'api/resale/create.php',
        'resale/buy'           => 'api/resale/buy.php',
        'resale/revoke'        => 'api/resale/revoke.php',
        'resale/browse'        => 'api/resale/browse.php',
        'resale/convert'       => 'api/resale/convert.php',
        
        // Flash Slots
        'flash/create'         => 'api/flash/create.php',
        'flash/list'           => 'api/flash/list.php',
        'flash/browse'         => 'api/flash/browse.php',
        
        // Reviews
        'reviews/create'       => 'api/reviews/create.php',
        'reviews/list'         => 'api/reviews/list.php',
        'reviews/respond'      => 'api/reviews/respond.php',
        'reviews/remove'       => 'api/reviews/remove.php',
        
        // Reliability
        'reliability/score'    => 'api/reliability/score.php',
        'reliability/history'  => 'api/reliability/history.php',
        
        // Notifications
        'notifications/list'   => 'api/notifications/list.php',
        'notifications/read'   => 'api/notifications/read.php',
        'notifications/count'  => 'api/notifications/count.php',
        
        // Coach
        'coach/profile'        => 'api/coach/profile.php',
        'coach/venues'         => 'api/coach/venues.php',
        'coach/sessions'       => 'api/coach/sessions.php',
        'coach/session-create' => 'api/coach/session_create.php',
        'coach/session-cancel' => 'api/coach/session_cancel.php',
        'coach/register-session'=> 'api/coach/register_session.php',
        'coach/dashboard'      => 'api/coach/dashboard.php',
        'coach/reviews'        => 'api/coach/reviews.php',
        'coach/review-create'  => 'api/coach/review_create.php',
        'coach/review-respond' => 'api/coach/review_respond.php',
        'coach/upload-nic'     => 'api/coach/upload_nic.php',
        'coach/venue-request'  => 'api/coach/venue_request.php',
        'coach/venue-approve'  => 'api/coach/venue_approve.php',
        
        // Admin
        'admin/venues'         => 'api/admin/venues.php',
        'admin/venue-action'   => 'api/admin/venue_action.php',
        'admin/users'          => 'api/admin/users.php',
        'admin/user-action'    => 'api/admin/user_action.php',
        'admin/disputes'       => 'api/admin/disputes.php',
        'admin/dispute-action' => 'api/admin/dispute_action.php',
        'admin/coach-verify'   => 'api/admin/coach_verify.php',
        'admin/review-remove'  => 'api/admin/review_remove.php',
        'admin/announcement-remove' => 'api/admin/announcement_remove.php',
        
        // Owner Dashboard
        'dashboard/stats'      => 'api/dashboard/stats.php',
        'dashboard/heatmap'    => 'api/dashboard/heatmap.php',
        'dashboard/customer'   => 'api/dashboard/customer.php',
        'dashboard/revenue'    => 'api/dashboard/revenue.php',
        
        // Community
        'community/diary'      => 'api/community/diary.php',
        'community/leaderboard'=> 'api/community/leaderboard.php',
        
        // Announcements
        'announcements/create' => 'api/announcements/create.php',
        'announcements/list'   => 'api/announcements/list.php',
    ];
    
    if (isset($apiRoutes[$apiRoute])) {
        $filePath = __DIR__ . '/' . $apiRoutes[$apiRoute];
        if (file_exists($filePath)) {
            require $filePath;
        } else {
            jsonError('API endpoint not yet implemented.', 501);
        }
    } else {
        jsonError('API endpoint not found.', 404);
    }
    exit;
}

// ─── Page Routes ────────────────────────────────────────────
// Pages render HTML using template includes

$pageRoutes = [
    ''                    => 'pages/home.php',
    'login'               => 'pages/login.php',
    'register'            => 'pages/register.php',
    'register/coach'      => 'pages/register_coach.php',
    'register/owner'      => 'pages/register_owner.php',
    'venues'              => 'pages/venues/list.php',
    'venue'               => 'pages/venues/detail.php',
    'book'                => 'pages/bookings/book.php',
    'my-bookings'         => 'pages/bookings/my_bookings.php',
    'booking'             => 'pages/bookings/detail.php',
    'resale'              => 'pages/resale/browse.php',
    'flash-deals'         => 'pages/flash/browse.php',
    'payment/return'      => 'pages/payments/return.php',
    'payment/cancel'      => 'pages/payments/cancel.php',
    'community/diary'     => 'pages/community/diary.php',
    'community/leaderboard'=> 'pages/community/leaderboard.php',
    'notifications'       => 'pages/notifications.php',
    
    // Owner pages
    'owner/dashboard'     => 'pages/dashboard/owner.php',
    'owner/venues'        => 'pages/dashboard/venues.php',
    'owner/venue/manage'  => 'pages/dashboard/venue_manage.php',
    'owner/bookings'      => 'pages/dashboard/bookings.php',
    'owner/flash'         => 'pages/dashboard/flash.php',
    'owner/announcements' => 'pages/dashboard/announcements.php',
    'owner/coaches'       => 'pages/dashboard/coaches.php',
    
    // Coach pages
    'coach/dashboard'     => 'pages/coach/dashboard.php',
    'coach/sessions'      => 'pages/coach/sessions.php',
    'coach/profile'       => 'pages/coach/profile.php',
    'coach/session'       => 'pages/coach/session_detail.php',
    
    // Public coach profile
    'coach/view'          => 'pages/coach/public_profile.php',
    
    // Coach session (public)
    'session'             => 'pages/coach/session_public.php',
    
    // Admin pages
    'admin/dashboard'     => 'pages/admin/dashboard.php',
    'admin/venues'        => 'pages/admin/venues.php',
    'admin/users'         => 'pages/admin/users.php',
    'admin/disputes'      => 'pages/admin/disputes.php',
    'admin/coaches'       => 'pages/admin/coaches.php',
    'admin/reviews'       => 'pages/admin/reviews.php',
];

if (isset($pageRoutes[$route])) {
    $filePath = __DIR__ . '/' . $pageRoutes[$route];
    if (file_exists($filePath)) {
        require $filePath;
    } else {
        // Page not yet implemented — show coming soon
        $pageTitle = 'Coming Soon';
        include __DIR__ . '/pages/layouts/header.php';
        echo '<div class="container" style="padding: 4rem 0; text-align: center;">
                <h1>🚧 Coming Soon</h1>
                <p class="text-muted">This page is under construction.</p>
                <a href="' . BASE_URL . '" class="btn btn--primary" style="margin-top: 1rem;">Go Home</a>
              </div>';
        include __DIR__ . '/pages/layouts/footer.php';
    }
} else {
    // 404 page
    http_response_code(404);
    $pageTitle = 'Page Not Found';
    $filePath = __DIR__ . '/pages/errors/404.php';
    if (file_exists($filePath)) {
        require $filePath;
    } else {
        echo '<!DOCTYPE html><html><head><title>404</title></head><body><h1>404 — Page Not Found</h1><a href="' . BASE_URL . '">Go Home</a></body></html>';
    }
}
