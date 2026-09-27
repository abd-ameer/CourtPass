<?php
class AnnouncementController extends Controller
{
    public function index(): void
    {
        $venues = array_values(array_filter((new VenueService())->ownerVenues(Auth::id()), fn (array $v) => $v['status'] === 'approved'));
        $announcements = [];
        foreach ($venues as $venue) {
            foreach ((new DiscoveryService())->venuePage($venue['slug'])['announcements'] ?? [] as $a) {
                $announcements[] = $a + ['venue_name' => $venue['name']];
            }
        }
        usort($announcements, fn (array $x, array $y) => strcmp($y['posted_at'], $x['posted_at']));
        $this->view('owner/announcements', [
            'title'         => 'Announcements',
            'venues'        => $venues,
            'announcements' => $announcements,
        ], 'dashboard');
    }

    public function store(): void
    {
        $this->verifyCsrf();
        // TODO: post an operational or promotional announcement for the owner's own venue.
        Session::flash('info', 'Announcements are not built yet.');
        $this->redirect('/owner/announcements');
    }

    public function moderation(): void
    {
        $this->view('admin/announcement-moderation', ['title' => 'Announcement Moderation'], 'dashboard');
    }

    public function remove(string $id): void
    {
        $this->verifyCsrf();
        // TODO: set the announcement status to removed.
        Session::flash('info', 'Announcement removal is not built yet.');
        $this->redirect('/admin/announcements');
    }
}
