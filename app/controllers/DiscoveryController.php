<?php
class DiscoveryController extends Controller
{
    public function home(): void
    {
        $browse = (new DiscoveryService())->browse();
        $sports = [];
        foreach ($browse['sports'] as $sport) {
            $count = count(array_filter($browse['venues'], fn (array $v) => in_array($sport['name'], $v['sports'], true)));
            $sports[] = $sport + ['venue_count' => $count];
        }

        $this->view('home/index', [
            'title'            => 'Book Sports Venues, Courts & Coaches in Sri Lanka',
            'sports'           => $sports,
            'cities'           => $browse['cities'],
            'featuredVenues'   => array_slice($browse['venues'], 0, 3),
            'coachingSessions' => array_slice((new CoachSessionService())->publicSessions(), 0, 3),
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

    /** A text query or form field; anything that is not a string counts as empty. */
    private function text(string $key, bool $fromQuery = true): string
    {
        $value = $fromQuery ? $this->request->query($key, '') : $this->request->input($key, '');
        return is_string($value) ? $value : '';
    }
}
