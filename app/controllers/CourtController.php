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
        $this->courtForm([], (int) $this->request->query('venue', 0), $this->defaultHours(), []);
    }

    public function store(): void
    {
        $this->verifyCsrf();
        $venueId = (int) $this->request->input('venue_id', 0);
        $court = [];
        foreach (['name', 'sport_type_id', 'hourly_rate'] as $field) {
            $value = $this->request->input($field);
            $court[$field] = is_string($value) ? trim($value) : '';
        }
        $hours = $this->hoursInput();

        $errors = (new Validator($court))
            ->required('name', 'Court name')->maxLength('name', 100)
            ->required('sport_type_id', 'Sport type')->integer('sport_type_id', 1)
            ->required('hourly_rate', 'Hourly rate')->decimal('hourly_rate', 1, 100000)
            ->errors();
        if ($venueId < 1) {
            $errors['venue_id'] = 'Choose a venue.';
        }
        if ($errors === []) {
            try {
                (new CourtService())->addCourt(Auth::id(), $venueId, $court['name'], (int) $court['sport_type_id'], (float) $court['hourly_rate'], $hours);
                Session::flash('success', "{$court['name']} added. Its slots are generated from the operating hours.");
                $this->redirect('/owner/venues/' . $venueId);
            } catch (ValidationException $e) {
                $errors = $e->errors();
            }
        }
        http_response_code(422);
        $this->courtForm($court, $venueId, $hours, $errors);
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
        $court = $this->ownCourt((int) $id);
        $this->hoursForm($court, $court['hours'], []);
    }

    public function updateHours(string $id): void
    {
        $this->verifyCsrf();
        $court = $this->ownCourt((int) $id);
        $hours = $this->hoursInput();
        try {
            (new CourtService())->updateHours(Auth::id(), (int) $court['id'], $hours);
            Session::flash('success', "Operating hours for {$court['name']} saved.");
            $this->redirect('/owner/venues/' . $court['venue_id']);
        } catch (ValidationException $e) {
            http_response_code(422);
            $this->hoursForm($court, $hours, $e->errors());
        }
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

    private function courtForm(array $court, int $venueId, array $hours, array $errors): void
    {
        $this->view('owner/edit-court', [
            'title'      => 'Add Court',
            'courtId'    => null,
            'venueId'    => $venueId,
            'court'      => $court,
            'hours'      => self::displayHours($hours),
            'venues'     => (new CourtService())->ownerCourtVenues(Auth::id()),
            'sportTypes' => $this->sportTypes(),
            'errors'     => $errors,
        ], 'dashboard');
    }

    private function hoursForm(array $court, array $hours, array $errors): void
    {
        $this->view('owner/operating-hours', [
            'title'     => 'Operating Hours',
            'courtId'   => (int) $court['id'],
            'courtName' => $court['name'],
            'venueId'   => (int) $court['venue_id'],
            'hours'     => self::displayHours($hours),
            'errors'    => $errors,
        ], 'dashboard');
    }

    /** The current owner's court with its hours, or the 404 page. */
    private function ownCourt(int $courtId): array
    {
        $court = (new CourtService())->ownerCourt(Auth::id(), $courtId);
        if ($court === null) {
            $this->notFound();
        }
        return $court;
    }

    /** Open days from the hours form. A closing time of 00:00 means midnight (24:00). */
    private function hoursInput(): array
    {
        $raw = $this->request->input('hours');
        $hours = [];
        for ($day = 1; $day <= 7; $day++) {
            $row = is_array($raw) && is_array($raw[$day] ?? null) ? $raw[$day] : [];
            if (empty($row['open_day'])) {
                continue;
            }
            $open = substr(is_string($row['open_time'] ?? null) ? trim($row['open_time']) : '', 0, 5);
            $close = substr(is_string($row['close_time'] ?? null) ? trim($row['close_time']) : '', 0, 5);
            $hours[$day] = ['open' => $open, 'close' => $close === '00:00' ? '24:00' : $close];
        }
        return $hours;
    }

    /** Time inputs cannot show 24:00, so midnight is shown as 00:00. */
    private static function displayHours(array $hours): array
    {
        foreach ($hours as $day => $h) {
            if ($h['close'] === '24:00') {
                $hours[$day]['close'] = '00:00';
            }
        }
        return $hours;
    }

    private function defaultHours(): array
    {
        $hours = [];
        for ($day = 1; $day <= 7; $day++) {
            $hours[$day] = ['open' => '06:00', 'close' => '22:00'];
        }
        return $hours;
    }

    private function sportTypes(): array
    {
        return (new VenueService())->sportTypes();
    }
}
