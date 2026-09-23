<?php
/**
 * Test all registered routes across Public, Customer, Owner, Coach, and Admin portals.
 */

$testRoutes = [
    // Public routes
    ['GET', '/', 'Home Landing'],
    ['GET', '/venues', 'Public Venues'],
    ['GET', '/venue-details', 'Public Venue Details'],
    ['GET', '/court-details', 'Public Court Details'],
    ['GET', '/coaching', 'Public Coaching'],
    ['GET', '/coach-profile', 'Public Coach Profile'],
    ['GET', '/reliability', 'Public Reliability'],
    ['GET', '/help', 'Public Help'],
    ['GET', '/login', 'Login Form'],
    ['GET', '/register-role', 'Register Role'],
    ['GET', '/register-customer', 'Register Customer'],
    ['GET', '/register-owner', 'Register Owner'],
    ['GET', '/register-coach', 'Register Coach'],

    // Customer portal routes
    ['GET', '/customer/dashboard', 'Customer Dashboard'],
    ['GET', '/customer/venues', 'Customer Venues'],
    ['GET', '/customer/venue-details', 'Customer Venue Details'],
    ['GET', '/customer/court-availability', 'Customer Court Availability'],
    ['GET', '/customer/create-booking', 'Customer Create Booking'],
    ['GET', '/customer/bookings', 'Customer Bookings'],
    ['GET', '/customer/booking-details', 'Customer Booking Details'],
    ['GET', '/customer/cancel-booking', 'Customer Cancel Booking'],
    ['GET', '/customer/reschedule-booking', 'Customer Reschedule Booking'],
    ['GET', '/customer/my-resales', 'Customer My Resales & Refund Management'],
    ['GET', '/customer/coaching', 'Customer Coaching'],
    ['GET', '/customer/session-details', 'Customer Session Details'],
    ['GET', '/customer/my-sessions', 'Customer My Sessions'],
    ['GET', '/customer/reliability', 'Customer Reliability'],
    ['GET', '/customer/reviews', 'Customer Reviews'],
    ['GET', '/customer/create-review', 'Customer Create Review'],
    ['GET', '/customer/notifications', 'Customer Notifications'],
    ['GET', '/customer/profile', 'Customer Profile'],

    // Owner portal routes
    ['GET', '/owner/dashboard', 'Owner Dashboard'],
    ['GET', '/owner/venues', 'Owner Venues'],
    ['GET', '/owner/add-venue', 'Owner Add Venue'],
    ['GET', '/owner/edit-venue', 'Owner Edit Venue'],
    ['GET', '/owner/delete-venue', 'Owner Delete Venue'],
    ['GET', '/owner/venue-details', 'Owner Venue Details'],
    ['GET', '/owner/courts', 'Owner Courts'],
    ['GET', '/owner/edit-court', 'Owner Edit Court'],
    ['GET', '/owner/operating-hours', 'Owner Operating Hours'],
    ['GET', '/owner/slot-management', 'Owner Slot Management'],
    ['GET', '/owner/bookings', 'Owner Bookings'],
    ['GET', '/owner/booking-details', 'Owner Booking Details'],
    ['GET', '/owner/manual-booking', 'Owner Manual Booking'],
    ['GET', '/owner/coaches', 'Owner Coaches'],
    ['GET', '/owner/revenue', 'Owner Revenue'],
    ['GET', '/owner/reviews', 'Owner Reviews'],
    ['GET', '/owner/reports', 'Owner Reports'],
    ['GET', '/owner/discounts', 'Owner Discounts'],
    ['GET', '/owner/flash-deals', 'Owner Flash Deals'],
    ['GET', '/owner/notifications', 'Owner Notifications'],
    ['GET', '/owner/profile', 'Owner Profile'],

    // Coach portal routes
    ['GET', '/coach/dashboard', 'Coach Dashboard'],
    ['GET', '/coach/sessions', 'Coach Sessions'],
    ['GET', '/coach/create-session', 'Coach Create Session'],
    ['GET', '/coach/edit-session', 'Coach Edit Session'],
    ['GET', '/coach/cancel-session', 'Coach Cancel Session'],
    ['GET', '/coach/session-details', 'Coach Session Details'],
    ['GET', '/coach/venues', 'Coach Venues'],
    ['GET', '/coach/request-venue', 'Coach Request Venue'],
    ['GET', '/coach/students', 'Coach Students'],
    ['GET', '/coach/earnings', 'Coach Earnings'],
    ['GET', '/coach/reviews', 'Coach Reviews'],
    ['GET', '/coach/disputes', 'Coach Disputes'],
    ['GET', '/coach/notifications', 'Coach Notifications'],
    ['GET', '/coach/profile', 'Coach Profile'],

    // Admin portal routes
    ['GET', '/admin/dashboard', 'Admin Dashboard'],
    ['GET', '/admin/users', 'Admin Users'],
    ['GET', '/admin/user-details', 'Admin User Details'],
    ['GET', '/admin/venues-pending', 'Admin Venues Pending'],
    ['GET', '/admin/venue-approval', 'Admin Venue Approval'],
    ['GET', '/admin/coaches-pending', 'Admin Coaches Pending'],
    ['GET', '/admin/coach-approval', 'Admin Coach Approval'],
    ['GET', '/admin/financials', 'Admin Financials'],
    ['GET', '/admin/payouts', 'Admin Payouts'],
    ['GET', '/admin/disputes-noshow', 'Admin Disputes No-show'],
    ['GET', '/admin/disputes-resale', 'Admin Disputes Resale'],
    ['GET', '/admin/disputes-coach', 'Admin Disputes Coach'],
    ['GET', '/admin/audit-logs', 'Admin Audit Logs'],
    ['GET', '/admin/settings', 'Admin Settings'],
];

require_once __DIR__ . '/../app/bootstrap.php';

$router = new Router();
require __DIR__ . '/../config/routes.php';

$passed = 0;
$failed = 0;

foreach ($testRoutes as [$method, $uri, $label]) {
    $_SERVER['REQUEST_METHOD'] = $method;
    $_SERVER['REQUEST_URI'] = $uri;
    $_SERVER['SCRIPT_NAME'] = '/public/index.php';

    $req = new Request();
    ob_start();
    try {
        $router->dispatch($req);
        $output = ob_get_clean();
        if (str_contains($output, 'Something went wrong') || str_contains($output, 'Fatal error') || str_contains($output, 'Parse error')) {
            echo "FAIL: [{$method} {$uri}] ({$label}) - error in output\n";
            $failed++;
        } else {
            echo "OK:   [{$method} {$uri}] ({$label}) - " . strlen($output) . " bytes\n";
            $passed++;
        }
    } catch (Throwable $e) {
        ob_end_clean();
        echo "FAIL: [{$method} {$uri}] ({$label}) - Exception: " . $e->getMessage() . "\n";
        $failed++;
    }
}

echo "\n===============================\n";
echo "SUMMARY: Passed: {$passed} | Failed: {$failed} | Total: " . count($testRoutes) . "\n";
echo "===============================\n";
