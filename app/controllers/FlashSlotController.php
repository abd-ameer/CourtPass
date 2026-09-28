<?php
class FlashSlotController extends Controller
{
    public function index(): void
    {
        $service = new VenueService();
        $courts = [];
        foreach ($service->ownerVenues(Auth::id()) as $venue) {
            foreach ($service->ownerVenue(Auth::id(), $venue['id'])['courts'] as $court) {
                $courts[] = $court + ['venue_name' => $venue['name']];
            }
        }
        $this->view('owner/flash-slots', [
            'title'  => 'Flash Deals',
            'courts' => $courts,
            'deals'  => (new DiscoveryService())->ownerFlashDeals(Auth::id()),
        ], 'dashboard');
    }

    public function store(): void
    {
        $this->verifyCsrf();
        // TODO: mark an unbooked, unblocked, non-coaching slot as a flash deal below the court rate.
        Session::flash('info', 'Flash deals are not built yet.');
        $this->redirect('/owner/flash-slots');
    }
}
