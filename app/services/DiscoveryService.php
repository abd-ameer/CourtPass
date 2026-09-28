<?php
class DiscoveryService
{
    private const SEARCH_MAX = 100;

    /**
     * Approved, switched-on venues for the browse page, filtered by sport code, city and a name or address search,
     * with their active-review rating. Returns ['venues', 'sports', 'cities', 'filters'].
     */
    public function browse(string $sportCode = '', string $city = '', string $search = ''): array
    {
        $venueService = new VenueService();
        $sports = $venueService->sportTypes();
        $sportIds = array_column($sports, 'id', 'code');
        $sportCode = isset($sportIds[$sportCode]) ? $sportCode : '';
        $city = trim($city);
        $search = mb_substr(trim($search), 0, self::SEARCH_MAX);

        $cities = array_values(array_unique(array_column($venueService->publicVenues(), 'city')));
        sort($cities);

        $venues = $venueService->publicVenues($sportCode === '' ? null : $sportIds[$sportCode], $city, $search);
        $ratings = (new ReviewService())->venueRatings(array_column($venues, 'id'));
        $courts = new CourtService();
        $flashVenues = array_flip(array_column($this->availableNow(), 'venue_id'));
        foreach ($venues as $i => $venue) {
            $venues[$i] = self::withListingFields($venue, $ratings[$venue['id']]);
            $venues[$i]['has_flash'] = isset($flashVenues[$venue['id']]);
            $venues[$i]['first_court_id'] = $courts->venueCourts($venue['id'])[0]['id'] ?? null;
        }

        return [
            'venues'  => $venues,
            'sports'  => $sports,
            'cities'  => $cities,
            'filters' => ['sport' => $sportCode, 'city' => $city, 'q' => $search],
        ];
    }

    /**
     * The public page of an approved, switched-on venue: ['venue', 'courts', 'reviews', 'announcements'], or null.
     * Reviews and the average come from active reviews only, newest first.
     */
    public function venuePage(string $slug): ?array
    {
        $venue = (new VenueService())->publicVenueBySlug($slug);
        if ($venue === null) {
            return null;
        }
        $reviewService = new ReviewService();
        $flashCourts = array_flip(array_column($this->availableNow(), 'court_id'));
        $courts = array_map(fn (array $c) => [
            'id'          => $c['id'],
            'name'        => $c['name'],
            'sport'       => $c['sport_name'],
            'hourly_rate' => $c['hourly_rate'],
            'has_flash'   => isset($flashCourts[$c['id']]),
        ], $venue['courts']);
        $reviews = array_map(fn (array $r) => [
            'id'         => $r['id'],
            'author'     => $r['reviewer_name'],
            'rating'     => $r['rating'],
            'comment'    => $r['comment'],
            'created_at' => $r['created_at'],
            'response'   => $r['response_text'],
        ], $reviewService->venueReviews($venue['id']));
        $announcements = array_map(fn (array $a) => [
            'type'      => $a['type'],
            'title'     => $a['title'],
            'body'      => $a['body'],
            'posted_at' => $a['created_at'],
        ], (new AnnouncementModel())->activeForVenue($venue['id']));

        $listing = self::withListingFields($venue, $reviewService->venueRatings([$venue['id']])[$venue['id']]);
        $listing['has_flash'] = $flashCourts !== [] && array_filter($courts, fn (array $c) => $c['has_flash']) !== [];
        return [
            'venue'         => $listing,
            'courts'        => $courts,
            'reviews'       => $reviews,
            'announcements' => $announcements,
        ];
    }

    /** Flash deals for the Available Now page: active, upcoming, on listed venues and active courts, soonest first. */
    public function availableNow(): array
    {
        return array_map(fn (array $f) => self::presentFlash($f), (new FlashSlotModel())->activeUpcoming(now()));
    }

    /** The owner's active, upcoming flash deals, soonest first. */
    public function ownerFlashDeals(int $ownerId): array
    {
        return array_map(fn (array $f) => self::presentFlash($f), (new FlashSlotModel())->activeForOwner($ownerId, now()));
    }

    private static function presentFlash(array $f): array
    {
        foreach (['id', 'court_id', 'venue_id'] as $key) {
            $f[$key] = (int) $f[$key];
        }
        $f['original_price'] = (float) $f['original_price'];
        $f['discounted_price'] = (float) $f['discounted_price'];
        $f['percent_off'] = (int) round(100 - $f['discounted_price'] / $f['original_price'] * 100);
        $f['starts_at'] = $f['slot_date'] . ' ' . $f['start_time'];
        $f['start'] = substr($f['start_time'], 0, 5);
        $f['end'] = date('H:i', strtotime($f['starts_at'] . ' +1 hour'));
        return $f;
    }

    /** Sport names, rating fields and has_flash (set by the caller) for the venue card and page. */
    private static function withListingFields(array $venue, array $rating): array
    {
        $venue['sports'] = array_column($venue['sports'], 'name');
        $venue['avg_rating'] = $rating['avg_rating'];
        $venue['review_count'] = $rating['review_count'];
        $venue['has_flash'] = false;
        return $venue;
    }
}
