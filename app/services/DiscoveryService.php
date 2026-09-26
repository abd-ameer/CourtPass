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
        foreach ($venues as $i => $venue) {
            $venues[$i] = self::withListingFields($venue, $ratings[$venue['id']]);
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
        $courts = array_map(fn (array $c) => [
            'id'          => $c['id'],
            'name'        => $c['name'],
            'sport'       => $c['sport_name'],
            'hourly_rate' => $c['hourly_rate'],
            'has_flash'   => false,
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

        return [
            'venue'         => self::withListingFields($venue, $reviewService->venueRatings([$venue['id']])[$venue['id']]),
            'courts'        => $courts,
            'reviews'       => $reviews,
            'announcements' => $announcements,
        ];
    }

    /** Sport names, rating fields and has_flash (flash deals are not built yet) for the venue card and page. */
    private static function withListingFields(array $venue, array $rating): array
    {
        $venue['sports'] = array_column($venue['sports'], 'name');
        $venue['avg_rating'] = $rating['avg_rating'];
        $venue['review_count'] = $rating['review_count'];
        $venue['has_flash'] = false;
        return $venue;
    }
}
