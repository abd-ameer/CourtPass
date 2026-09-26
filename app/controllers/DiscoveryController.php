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
        $this->view('public/venues', ['title' => 'Browse Venues'] + (new DiscoveryService())->browse(
            $this->text('sport'),
            $this->text('city'),
            $this->text('q')
        ));
    }

    public function venue(string $slug): void
    {
        $page = (new DiscoveryService())->venuePage($slug);
        if ($page === null) {
            $this->notFound();
        }
        $this->view('public/venue-details', ['title' => $page['venue']['name']] + $page);
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

    /** A text query or form field; anything that is not a string counts as empty. */
    private function text(string $key, bool $fromQuery = true): string
    {
        $value = $fromQuery ? $this->request->query($key, '') : $this->request->input($key, '');
        return is_string($value) ? $value : '';
    }
}
