<?php
class DiscoveryController extends Controller
{
    public function home(): void
    {
        // TODO: replace the sample data with the discovery, venue and coach session services.
        $sports = $this->sampleSports();

        $featuredVenues = $this->sampleVenues();

        $coachingSessions = [
            [
                'id' => 2, 'title' => 'Intermediate Rally Drills', 'sport' => 'Badminton',
                'venue_name' => 'Colombo Sports Hub', 'court_name' => 'Badminton Court 2',
                'coach_id' => 7, 'coach_name' => 'Ashan Weerasinghe', 'coach_verified' => true, 'coach_rating' => 4.0,
                'starts_at' => '2026-09-29 08:00:00', 'fee' => 1800.00, 'capacity' => 4, 'registered_count' => 1,
                'status' => 'open',
            ],
        ];

        $this->view('home/index', [
            'title'            => 'Book Sports Venues, Courts & Coaches in Sri Lanka',
            'sports'           => $sports,
            'featuredVenues'   => $featuredVenues,
            'coachingSessions' => $coachingSessions,
        ]);
    }

    public function venues(): void
    {
        // TODO: filter approved, active venues by sport, city and name in the discovery service.
        $this->view('public/venues', [
            'title'   => 'Browse Venues',
            'venues'  => $this->sampleVenues(),
            'sports'  => $this->sampleSports(),
            'cities'  => ['Colombo', 'Kandy'],
            'filters' => [
                'sport' => (string) $this->request->query('sport', ''),
                'city'  => (string) $this->request->query('city', ''),
                'q'     => (string) $this->request->query('q', ''),
            ],
        ]);
    }

    public function venue(string $slug): void
    {
        // TODO: load the approved venue by slug (404 otherwise) with its courts, active announcements and reviews.
        $venue = [
            'id' => 1, 'slug' => $slug, 'name' => 'Colombo Sports Hub', 'city' => 'Colombo',
            'address' => '45 Galle Road, Colombo 03', 'contact_phone' => '0112345678',
            'description' => 'Indoor futsal, badminton and table tennis courts. Air-conditioned, open 7 days.',
            'sports' => ['Futsal', 'Badminton', 'Table Tennis'], 'avg_rating' => 5.0, 'review_count' => 1,
        ];
        $courts = [
            ['id' => 1, 'name' => 'Futsal Court A', 'sport' => 'Futsal', 'hourly_rate' => 5000.00, 'has_flash' => true],
            ['id' => 2, 'name' => 'Futsal Court B', 'sport' => 'Futsal', 'hourly_rate' => 5000.00, 'has_flash' => false],
            ['id' => 3, 'name' => 'Badminton Court 1', 'sport' => 'Badminton', 'hourly_rate' => 2000.00, 'has_flash' => false],
        ];
        $announcements = [
            ['type' => 'operational', 'title' => 'New LED lighting on the badminton courts', 'body' => 'All badminton courts now have glare-free overhead lighting.', 'posted_at' => '2026-09-20 09:00:00'],
        ];
        $reviews = [
            ['author' => 'Saman Kumara', 'rating' => 5, 'comment' => 'Great courts and easy check-in.', 'created_at' => '2026-09-19 20:00:00',
             'response' => 'Thanks Saman, see you next week!'],
        ];
        $this->view('public/venue-details', [
            'title'         => $venue['name'],
            'venue'         => $venue,
            'courts'        => $courts,
            'announcements' => $announcements,
            'reviews'       => $reviews,
        ]);
    }

    public function help(): void
    {
        $this->view('public/help', ['title' => 'Help Centre']);
    }

    private function sampleSports(): array
    {
        return [
            ['code' => 'futsal', 'name' => 'Futsal', 'venue_count' => 1],
            ['code' => 'badminton', 'name' => 'Badminton', 'venue_count' => 1],
            ['code' => 'pickleball', 'name' => 'Pickleball', 'venue_count' => 0],
            ['code' => 'squash', 'name' => 'Squash', 'venue_count' => 1],
            ['code' => 'billiards', 'name' => 'Billiards', 'venue_count' => 1],
            ['code' => 'carrom', 'name' => 'Carrom', 'venue_count' => 1],
            ['code' => 'table_tennis', 'name' => 'Table Tennis', 'venue_count' => 1],
        ];
    }

    private function sampleVenues(): array
    {
        return [
            [
                'id' => 1, 'slug' => 'colombo-sports-hub', 'name' => 'Colombo Sports Hub', 'city' => 'Colombo',
                'address' => '45 Galle Road, Colombo 03', 'sports' => ['Futsal', 'Badminton', 'Table Tennis'],
                'court_count' => 5, 'min_rate' => 1000.00, 'avg_rating' => 5.0, 'review_count' => 1,
                'has_flash' => true, 'first_court_id' => 1,
            ],
            [
                'id' => 2, 'slug' => 'kandy-court-zone', 'name' => 'Kandy Court Zone', 'city' => 'Kandy',
                'address' => '12 Peradeniya Road, Kandy', 'sports' => ['Squash', 'Billiards', 'Carrom'],
                'court_count' => 3, 'min_rate' => 800.00, 'avg_rating' => null, 'review_count' => 0,
                'has_flash' => false, 'first_court_id' => 6,
            ],
        ];
    }
}
