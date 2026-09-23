<?php
class PublicController extends Controller
{
    public function venues(): void
    {
        $this->view('public/venues', [
            'title' => 'Browse Sports Venues & Courts',
            'selected_sport' => $this->request->query('sport', ''),
            'selected_city' => $this->request->query('city', ''),
        ]);
    }

    public function venueDetails(): void
    {
        $id = $this->request->query('id', 'venue-1');
        $this->view('public/venue-details', ['title' => 'Venue Details', 'venueId' => $id]);
    }

    public function courtDetails(): void
    {
        $court = $this->request->query('court', 'c1');
        $this->view('public/court-details', ['title' => 'Court Slot Availability', 'courtId' => $court]);
    }

    public function coaching(): void
    {
        $this->view('public/coaching', ['title' => 'Coaching Clinics & Masterclasses']);
    }

    public function coachProfile(): void
    {
        $id = $this->request->query('id', 'coach-1');
        $this->view('public/coach-profile', ['title' => 'Coach Profile & Accreditations', 'coachId' => $id]);
    }

    public function resale(): void
    {
        $this->view('customer/resale', ['title' => 'Resale Ticket Marketplace'], 'main');
    }

    public function reliability(): void
    {
        $this->view('customer/reliability', ['title' => 'Reliability Score & Tier Guidelines'], 'main');
    }

    public function help(): void
    {
        $this->view('public/help', ['title' => 'CourtPass Support & Help Centre']);
    }

    public function sessionDetails(): void
    {
        $id = $this->request->query('id', 'session-101');
        $this->view('customer/session-details', ['title' => 'Session Registration', 'sessionId' => $id], 'main');
    }
}
