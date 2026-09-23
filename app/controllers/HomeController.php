<?php
class HomeController extends Controller
{
    public function index(): void
    {
        $sports = [
            ['name' => 'Badminton', 'count' => '14 Venues', 'slug' => 'badminton', 'active' => true],
            ['name' => 'Futsal', 'count' => '9 Venues', 'slug' => 'futsal', 'active' => false],
            ['name' => 'Pickleball', 'count' => '6 Arenas', 'slug' => 'pickleball', 'active' => false],
            ['name' => 'Squash', 'count' => '5 Clubs', 'slug' => 'squash', 'active' => false],
            ['name' => 'Table Tennis', 'count' => '11 Halls', 'slug' => 'table-tennis', 'active' => false],
            ['name' => 'Carrom', 'count' => '8 Clubs', 'slug' => 'carrom', 'active' => false],
            ['name' => 'Billiards', 'count' => '7 Lounges', 'slug' => 'billiards', 'active' => false],
        ];

        $featuredVenues = [
            [
                'id' => 'venue-1',
                'name' => 'Colombo Futsal Club',
                'image' => 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?auto=format&fit=crop&w=800&q=80',
                'badges' => [
                    ['type' => 'badge-active', 'label' => 'Active'],
                    ['type' => 'badge-flash', 'label' => 'Flash Slots'],
                ],
                'price' => 'LKR 4,500',
                'category' => 'Futsal & Badminton',
                'rating' => '4.8 (124)',
                'location' => 'Marine Drive, Dehiwala · 3 Courts',
                'top_player' => 'Kasun J. (26 hrs played)',
                'court_id' => 'c1',
            ],
            [
                'id' => 'venue-2',
                'name' => 'CR&FC Badminton Complex',
                'image' => 'https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?auto=format&fit=crop&w=800&q=80',
                'badges' => [
                    ['type' => 'badge-active', 'label' => 'Active'],
                    ['type' => 'badge-confirmed', 'label' => 'BWF Certified'],
                ],
                'price' => 'LKR 2,800',
                'category' => 'Badminton & Squash',
                'rating' => '4.9 (210)',
                'location' => 'Longdon Place, Colombo 07 · 3 Courts',
                'top_player' => 'Coach Dilshan (32 hrs played)',
                'court_id' => 'c4',
            ],
            [
                'id' => 'venue-3',
                'name' => 'ProPickle Arena Colombo',
                'image' => 'https://images.unsplash.com/photo-1599474924187-334a4ae5bd3c?auto=format&fit=crop&w=800&q=80',
                'badges' => [
                    ['type' => 'badge-active', 'label' => 'Active'],
                    ['type' => 'badge-pending', 'label' => 'New Arena'],
                ],
                'price' => 'LKR 3,000',
                'category' => 'Pickleball',
                'rating' => '4.7 (88)',
                'location' => 'Pelawatte, Battaramulla · 2 Courts',
                'top_player' => 'Amanda W. (28 hrs played)',
                'court_id' => 'c7',
            ],
        ];

        $coachingSessions = [
            [
                'id' => 'session-101',
                'border_color' => 'var(--color-primary)',
                'badge' => 'Badminton Clinic',
                'status_badge' => ['class' => 'badge-confirmed', 'label' => '2 Spots Left'],
                'title' => 'Badminton Jump Smash & Forecourt Mastery',
                'venue' => 'CR&FC Badminton Complex · Court 1',
                'coach_initials' => 'DP',
                'coach_name' => 'Coach Dilshan Perera',
                'coach_verified' => true,
                'coach_cred' => 'SL Senior National Squad · Rating 4.95',
                'datetime' => 'Sept 24 · 07:00 PM',
                'fee' => 'LKR 3,500',
                'is_full' => false,
            ],
            [
                'id' => 'session-102',
                'border_color' => 'var(--color-navy)',
                'badge' => 'Futsal Clinic',
                'status_badge' => ['class' => 'badge-full', 'label' => 'Session Full'],
                'title' => 'Futsal High-Press Tactical Clinic',
                'venue' => 'Colombo Futsal Club · Turf 1',
                'coach_initials' => 'SF',
                'coach_name' => 'Coach Shanilka Fernando',
                'coach_verified' => true,
                'coach_cred' => 'AFC "C" License Holder · Rating 4.88',
                'datetime' => 'Sept 25 · 08:00 PM',
                'fee' => 'LKR 4,000',
                'is_full' => true,
            ],
            [
                'id' => 'session-103',
                'border_color' => 'var(--color-primary)',
                'badge' => 'Pickleball Clinic',
                'status_badge' => ['class' => 'badge-confirmed', 'label' => '2 Spots Left'],
                'title' => 'Pickleball Fundamentals & Match Drills',
                'venue' => 'ProPickle Arena Colombo · Court 1',
                'coach_initials' => 'AW',
                'coach_name' => 'Coach Amanda Wijesinghe',
                'coach_verified' => true,
                'coach_cred' => 'IPTPA Certified Instructor · Rating 4.92',
                'datetime' => 'Sept 26 · 05:00 PM',
                'fee' => 'LKR 3,000',
                'is_full' => false,
            ],
        ];

        $this->view('home/index', [
            'title' => 'Book Sports Venues, Courts & Coaches in Sri Lanka',
            'sports' => $sports,
            'featuredVenues' => $featuredVenues,
            'coachingSessions' => $coachingSessions,
        ]);
    }
}
