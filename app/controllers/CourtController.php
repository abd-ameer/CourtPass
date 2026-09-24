<?php
class CourtController extends Controller
{
    public function index(): void
    {
        $this->view('owner/courts', [
            'title'   => 'Courts',
            'venueId' => (int) $this->request->query('venue', 0),
        ], 'dashboard');
    }

    public function create(): void
    {
        $this->view('owner/edit-court', [
            'title'      => 'Add Court',
            'courtId'    => null,
            'venueId'    => (int) $this->request->query('venue', 0),
            'court'      => [],
            'hours'      => $this->sampleHours(),
            'venues'     => $this->sampleVenues(),
            'sportTypes' => $this->sportTypes(),
            'errors'     => [],
        ], 'dashboard');
    }

    public function store(): void
    {
        $this->verifyCsrf();
        // TODO: validate and add the court with its operating hours to the owner's venue.
        Session::flash('info', 'Adding courts is not built yet.');
        $this->redirect('/owner/courts');
    }

    public function edit(string $id): void
    {
        // TODO: load the owner's own court; 404 when it belongs to someone else.
        $court = ['name' => 'Futsal Court A', 'sport_type_id' => 1, 'hourly_rate' => '5000.00', 'is_active' => 1];
        $this->view('owner/edit-court', [
            'title'      => 'Edit Court',
            'courtId'    => (int) $id,
            'venueId'    => 1,
            'court'      => $court,
            'hours'      => [],
            'venues'     => [],
            'sportTypes' => $this->sportTypes(),
            'errors'     => [],
        ], 'dashboard');
    }

    public function update(string $id): void
    {
        $this->verifyCsrf();
        // TODO: validate and update the owner's own court.
        Session::flash('info', 'Court editing is not built yet.');
        $this->redirect('/owner/courts');
    }

    public function hours(string $id): void
    {
        // TODO: load the court and its court_operating_hours rows.
        $this->view('owner/operating-hours', [
            'title'     => 'Operating Hours',
            'courtId'   => (int) $id,
            'courtName' => 'Futsal Court A',
            'hours'     => $this->sampleHours(),
        ], 'dashboard');
    }

    public function updateHours(string $id): void
    {
        $this->verifyCsrf();
        // TODO: save the court's operating hours per day of week.
        Session::flash('info', 'Operating hours are not built yet.');
        $this->redirect('/owner/courts/' . (int) $id . '/hours');
    }

    public function slots(): void
    {
        $this->view('owner/slot-management', [
            'title'   => 'Slot Management',
            'courtId' => (int) $this->request->query('court', 0),
            'date'    => (string) $this->request->query('date', date('Y-m-d')),
        ], 'dashboard');
    }

    public function block(): void
    {
        $this->verifyCsrf();
        // TODO: block a free slot (conflict check runs first).
        Session::flash('info', 'Slot blocking is not built yet.');
        $this->redirect('/owner/slots');
    }

    public function unblock(string $id): void
    {
        $this->verifyCsrf();
        // TODO: remove an owner block.
        Session::flash('info', 'Slot blocking is not built yet.');
        $this->redirect('/owner/slots');
    }

    private function sampleHours(): array
    {
        $hours = [];
        for ($day = 1; $day <= 7; $day++) {
            $hours[$day] = ['open' => '06:00', 'close' => '22:00'];
        }
        return $hours;
    }

    private function sampleVenues(): array
    {
        // TODO: the owner's approved venues.
        return [['id' => 1, 'name' => 'Colombo Sports Hub'], ['id' => 2, 'name' => 'Kandy Court Zone']];
    }

    private function sportTypes(): array
    {
        // TODO: read from sport_types.
        return [
            ['id' => 1, 'name' => 'Futsal'], ['id' => 2, 'name' => 'Badminton'], ['id' => 3, 'name' => 'Pickleball'],
            ['id' => 4, 'name' => 'Squash'], ['id' => 5, 'name' => 'Billiards'], ['id' => 6, 'name' => 'Carrom'],
            ['id' => 7, 'name' => 'Table Tennis'],
        ];
    }
}
