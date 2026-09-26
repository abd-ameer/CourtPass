<?php
/**
 * DiscoveryService: browse venues and the public venue page, with review figures.
 * Seed ids: venue 1 Colombo Sports Hub (approved, courts 1 to 5, review 1 rated 5), venue 2 Kandy Court Zone
 * (approved, courts 6 to 8, no reviews), venue 3 Galle Racquet Club (pending, no courts); owner 2 kamal, admin 1.
 */
class DiscoveryServiceTest extends DatabaseTestCase
{
    private DiscoveryService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new DiscoveryService();
    }

    public function testBrowseListsOnlyApprovedActiveVenues(): void
    {
        $page = $this->service->browse();

        $this->assertSame([1, 2], array_column($page['venues'], 'id'));
        $this->assertSame(['Colombo', 'Kandy'], $page['cities']);
        $hub = $page['venues'][0];
        $this->assertSame(5.0, $hub['avg_rating']);
        $this->assertSame(1, $hub['review_count']);
        $this->assertSame(1, $hub['first_court_id']);
        $this->assertSame(['Futsal', 'Badminton', 'Table Tennis'], $hub['sports']);
        $this->assertFalse($hub['has_flash']);
        $this->assertNull($page['venues'][1]['avg_rating']);

        $this->execute('UPDATE venues SET is_active = 0 WHERE id = 2');
        $this->assertSame([1], array_column($this->service->browse()['venues'], 'id'));
    }

    public function testBrowseFilters(): void
    {
        $this->assertSame([1], array_column($this->service->browse('badminton')['venues'], 'id'));
        $this->assertSame([2], array_column($this->service->browse('', 'Kandy')['venues'], 'id'));
        $this->assertSame([2], array_column($this->service->browse('', '', ' peradeniya ')['venues'], 'id'));
        $this->assertSame([], $this->service->browse('squash', 'Colombo')['venues']);

        $unknown = $this->service->browse('curling');
        $this->assertSame('', $unknown['filters']['sport'], 'An unknown sport code means all sports.');
        $this->assertCount(2, $unknown['venues']);
    }

    public function testAnApprovedVenueWithoutCourtsIsListedWithoutSlots(): void
    {
        (new VenueService())->approve(1, 3);

        $galle = array_column($this->service->browse()['venues'], null, 'id')[3];
        $this->assertSame(0, $galle['court_count']);
        $this->assertNull($galle['min_rate']);
        $this->assertNull($galle['first_court_id']);

        $page = $this->service->venuePage('galle-racquet-club');
        $this->assertSame([], $page['courts']);
        $this->assertSame([], $page['reviews']);
    }

    public function testVenuePageShowsActiveReviewsWithTheAverage(): void
    {
        $this->insertReview(100, 'DATE_SUB(NOW(), INTERVAL 1 DAY)', 2);

        $page = $this->service->venuePage('colombo-sports-hub');

        $this->assertSame('Colombo Sports Hub', $page['venue']['name']);
        $this->assertSame([1, 100], array_column($page['reviews'], 'id'), 'Newest first.');
        $this->assertSame(3.5, $page['venue']['avg_rating']);
        $this->assertSame(2, $page['venue']['review_count']);
        $this->assertSame('Thank you, see you again!', $page['reviews'][0]['response']);
        $this->assertSame('Saman Fernando', $page['reviews'][0]['author']);
        $this->assertSame([1, 2, 3, 4, 5], array_column($page['courts'], 'id'));
        $this->assertSame(['Weekday morning discount', 'Court B maintenance'], array_column($page['announcements'], 'title'));

        (new ReviewService())->remove(1, 100, 'Spam.');
        $page = $this->service->venuePage('colombo-sports-hub');
        $this->assertSame([1], array_column($page['reviews'], 'id'));
        $this->assertSame(5.0, $page['venue']['avg_rating']);
    }

    public function testVenuePageIsNotFoundUnlessApprovedAndActive(): void
    {
        $this->assertNull($this->service->venuePage('galle-racquet-club'), 'Pending.');
        $this->assertNull($this->service->venuePage('no-such-venue'));

        $this->execute('UPDATE venues SET is_active = 0 WHERE id = 1');
        $this->assertNull($this->service->venuePage('colombo-sports-hub'), 'Switched off.');
        $this->assertSame('active', $this->fetchValue('SELECT status FROM reviews WHERE id = 1'), 'Reviews are kept while the venue is off.');
    }

    /** A second checked-in booking of saman at venue 1 with an active review created at $createdAt. */
    private function insertReview(int $id, string $createdAt, int $rating): void
    {
        $date = date('Y-m-d', strtotime('-2 days'));
        $this->execute("INSERT INTO bookings (id, customer_id, court_id, slot_date, start_time, amount, payment_method, status, confirmed_at)
                        VALUES ({$id}, 4, 5, '{$date}', '10:00:00', 1000, 'online', 'completed', NOW())");
        $this->execute("INSERT INTO check_ins (booking_id, checked_in_by, checked_in_at) VALUES ({$id}, 2, '{$date} 10:00:00')");
        $this->execute("INSERT INTO reviews (id, reviewer_id, venue_id, booking_id, rating, comment, created_at)
                        VALUES ({$id}, 4, 1, {$id}, {$rating}, 'Busy evening.', {$createdAt})");
    }
}
