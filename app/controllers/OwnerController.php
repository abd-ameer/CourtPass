<?php
class OwnerController extends Controller
{
    private function show(string $view, string $title, array $data = []): void
    {
        $this->view('owner/' . $view, array_merge(['title' => $title, 'active_role' => 'owner'], $data), 'dashboard');
    }

    public function dashboard(): void
    {
        $this->show('dashboard', 'Venue Owner Dashboard');
    }

    public function venues(): void
    {
        $this->show('venues', 'My Venues');
    }

    public function addVenue(): void
    {
        $this->show('add-venue', 'Add New Sports Venue');
    }

    public function editVenue(): void
    {
        $id = $this->request->query('id', 'venue-1');
        $this->show('edit-venue', 'Edit Venue', ['venueId' => $id]);
    }

    public function deleteVenue(): void
    {
        $id = $this->request->query('id', 'venue-1');
        $this->show('delete-venue', 'Delete Venue', ['venueId' => $id]);
    }

    public function venueDetails(): void
    {
        $id = $this->request->query('id', 'venue-1');
        $this->show('venue-details', 'Venue Details', ['venueId' => $id]);
    }

    public function courts(): void
    {
        $this->show('courts', 'Court Management');
    }

    public function editCourt(): void
    {
        $id = $this->request->query('id', 'c1');
        $this->show('edit-court', 'Edit Court', ['courtId' => $id]);
    }

    public function operatingHours(): void
    {
        $this->show('operating-hours', 'Operating Hours');
    }

    public function slotManagement(): void
    {
        $this->show('slot-management', 'Slot Grid & Blocks');
    }

    public function bookings(): void
    {
        $this->show('bookings', 'Bookings Table');
    }

    public function bookingDetails(): void
    {
        $id = $this->request->query('id', 'BK-9021');
        $this->show('booking-details', 'Booking Details', ['bookingId' => $id]);
    }

    public function checkIn(): void
    {
        $this->show('check-in', 'Customer Check-in');
    }

    public function flashSlots(): void
    {
        $this->show('flash-slots', 'Flash Deals & Discounts');
    }

    public function announcements(): void
    {
        $this->show('announcements', 'Venue Announcements');
    }

    public function coachRequests(): void
    {
        $this->show('coach-requests', 'Coach Partnership Requests');
    }

    public function customers(): void
    {
        $this->show('customers', 'Customer Intelligence & Leaderboards');
    }

    public function revenue(): void
    {
        $this->show('revenue', 'Revenue & Payout Analytics');
    }

    public function utilisation(): void
    {
        $this->show('utilisation', 'Court Utilisation & Peak Hours');
    }

    public function notifications(): void
    {
        $this->show('notifications', 'Venue Notifications');
    }

    public function profile(): void
    {
        $this->show('profile', 'Venue Settings & Owner Profile');
    }
}
