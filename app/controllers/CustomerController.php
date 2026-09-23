<?php
class CustomerController extends Controller
{
    private function show(string $view, string $title, array $data = []): void
    {
        $this->view('customer/' . $view, array_merge(['title' => $title, 'active_role' => 'customer'], $data), 'dashboard');
    }

    public function dashboard(): void
    {
        $this->show('dashboard', 'Customer Dashboard');
    }

    public function venues(): void
    {
        $this->show('venues', 'Browse Venues');
    }

    public function venueDetails(): void
    {
        $id = $this->request->query('id', 'venue-1');
        $this->show('venue-details', 'Venue Details', ['venueId' => $id]);
    }

    public function courtAvailability(): void
    {
        $this->show('court-availability', 'Court Availability Grid');
    }

    public function createBooking(): void
    {
        $this->show('create-booking', 'Book Court Slot');
    }

    public function bookings(): void
    {
        $this->show('bookings', 'My Bookings');
    }

    public function bookingDetails(): void
    {
        $id = $this->request->query('id', 'BK-9021');
        $this->show('booking-details', 'Booking Details', ['bookingId' => $id]);
    }

    public function cancelBooking(): void
    {
        $this->show('cancel-booking', 'Cancel Booking');
    }

    public function rescheduleBooking(): void
    {
        $this->show('reschedule-booking', 'Reschedule Slot');
    }

    public function resale(): void
    {
        $this->show('resale', 'Resale Market');
    }

    public function myResales(): void
    {
        $this->show('my-resales', 'My Resale Listings');
    }

    public function coaching(): void
    {
        $this->show('coaching', 'Coaching Sessions');
    }

    public function sessionDetails(): void
    {
        $id = $this->request->query('id', 'session-101');
        $this->show('session-details', 'Session Details', ['sessionId' => $id]);
    }

    public function mySessions(): void
    {
        $this->show('my-sessions', 'My Coaching Registrations');
    }

    public function reliability(): void
    {
        $this->show('reliability', 'Reliability Score & Tier');
    }

    public function reviews(): void
    {
        $this->show('reviews', 'My Reviews');
    }

    public function createReview(): void
    {
        $this->show('create-review', 'Submit Venue Review');
    }

    public function notifications(): void
    {
        $this->show('notifications', 'Customer Notifications');
    }

    public function profile(): void
    {
        $this->show('profile', 'Profile & Settings');
    }
}
