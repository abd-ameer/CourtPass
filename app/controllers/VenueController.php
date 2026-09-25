<?php
class VenueController extends Controller
{
    public function ownerForm(): void
    {
        $this->redirectIfLoggedIn();
        $this->view('auth/register-owner', ['title' => 'Venue Owner Sign Up', 'old' => [], 'errors' => []]);
    }

    public function registerOwner(): void
    {
        $this->verifyCsrf();
        $this->redirectIfLoggedIn();
        $data = $this->request->all();
        $errors = AuthController::accountErrors($data);
        $old = AuthController::accountOld($data);

        if ($errors !== []) {
            http_response_code(422);
            $this->view('auth/register-owner', ['title' => 'Venue Owner Sign Up', 'old' => $old, 'errors' => $errors]);
            return;
        }

        try {
            $user = (new AuthService())->registerOwner($data);
        } catch (ValidationException $e) {
            http_response_code(422);
            $this->view('auth/register-owner', ['title' => 'Venue Owner Sign Up', 'old' => $old, 'errors' => $e->errors()]);
            return;
        }

        Auth::login($user);
        Session::flash('success', 'Welcome to CourtPass, ' . $user['name'] . '! Register your first venue to get started.');
        $this->redirect('/owner/venues/create');
    }

    public function dashboard(): void
    {
        $this->view('owner/dashboard', ['title' => 'Owner Dashboard'], 'dashboard');
    }

    public function index(): void
    {
        $this->view('owner/venues', [
            'title'  => 'My Venues',
            'venues' => (new VenueService())->ownerVenues(Auth::id()),
        ], 'dashboard');
    }

    public function create(): void
    {
        $this->venueForm('owner/add-venue', 'Register a Venue', ['venue' => []]);
    }

    public function store(): void
    {
        $this->verifyCsrf();
        [$data, $errors] = $this->venueInput();
        if ($errors === []) {
            try {
                $id = (new VenueService())->create(Auth::id(), $data);
                Session::flash('success', 'Venue submitted. It stays Pending until the Platform Admin approves it.');
                $this->redirect('/owner/venues/' . $id);
            } catch (ValidationException $e) {
                $errors = $e->errors();
            }
        }
        http_response_code(422);
        $this->venueForm('owner/add-venue', 'Register a Venue', ['venue' => $data, 'errors' => $errors]);
    }

    public function show(string $id): void
    {
        $venue = $this->ownVenue((int) $id);
        $this->view('owner/venue-details', ['title' => $venue['name'], 'venue' => $venue], 'dashboard');
    }

    public function edit(string $id): void
    {
        $venue = $this->ownVenue((int) $id);
        if (!$venue['can_edit']) {
            Session::flash('error', 'This venue was deactivated by the platform and cannot be edited.');
            $this->redirect('/owner/venues/' . $venue['id']);
        }
        $venue['sport_type_ids'] = array_column($venue['sports'], 'id');
        $this->venueForm('owner/edit-venue', 'Edit Venue', ['venueId' => $venue['id'], 'venue' => $venue, 'status' => $venue['status']]);
    }

    public function update(string $id): void
    {
        $this->verifyCsrf();
        $venue = $this->ownVenue((int) $id);
        [$data, $errors] = $this->venueInput();
        if ($errors === []) {
            try {
                $resubmitted = (new VenueService())->update(Auth::id(), $venue['id'], $data);
                Session::flash('success', $resubmitted
                    ? 'Venue updated and sent back to the Platform Admin for approval.'
                    : 'Venue details saved.');
                $this->redirect('/owner/venues/' . $venue['id']);
            } catch (ValidationException $e) {
                if (isset($e->errors()['venue'])) {
                    Session::flash('error', $e->getMessage());
                    $this->redirect('/owner/venues/' . $venue['id']);
                }
                $errors = $e->errors();
            }
        }
        http_response_code(422);
        $this->venueForm('owner/edit-venue', 'Edit Venue', [
            'venueId' => $venue['id'], 'venue' => $data + ['name' => $venue['name']], 'status' => $venue['status'], 'errors' => $errors,
        ]);
    }

    public function confirmDeactivate(string $id): void
    {
        $venue = $this->ownVenue((int) $id);
        if (!$venue['can_deactivate']) {
            Session::flash('info', 'Only an approved, active venue can be deactivated.');
            $this->redirect('/owner/venues/' . $venue['id']);
        }
        $this->view('owner/deactivate-venue', ['title' => 'Deactivate Venue', 'venue' => $venue], 'dashboard');
    }

    public function deactivate(string $id): void
    {
        $this->verifyCsrf();
        $venue = $this->ownVenue((int) $id);
        $this->switchVenue($venue, fn (VenueService $s) => $s->deactivate(Auth::id(), $venue['id']),
            "{$venue['name']} is deactivated. It is hidden from public listings and takes no new bookings.");
    }

    public function activate(string $id): void
    {
        $this->verifyCsrf();
        $venue = $this->ownVenue((int) $id);
        $this->switchVenue($venue, fn (VenueService $s) => $s->activate(Auth::id(), $venue['id']),
            "{$venue['name']} is active again and open for bookings.");
    }

    public function adminDashboard(): void
    {
        $this->view('admin/dashboard', ['title' => 'Admin Dashboard'], 'dashboard');
    }

    public function adminIndex(): void
    {
        $this->view('admin/venue-approvals', [
            'title'  => 'Venue Approvals',
            'venues' => (new VenueService())->pendingVenues(),
        ], 'dashboard');
    }

    public function adminShow(string $id): void
    {
        $venue = (new VenueService())->adminVenue((int) $id);
        if ($venue === null) {
            $this->notFound();
        }
        $this->view('admin/venue-details', ['title' => 'Venue Review', 'venue' => $venue], 'dashboard');
    }

    public function approve(string $id): void
    {
        $this->verifyCsrf();
        $this->adminDecision((int) $id, fn (VenueService $s) => $s->approve(Auth::id(), (int) $id),
            'Venue approved. The owner has been notified.');
    }

    public function reject(string $id): void
    {
        $this->verifyCsrf();
        $reason = $this->reasonInput();
        if ($reason !== null) {
            $this->adminDecision((int) $id, fn (VenueService $s) => $s->reject(Auth::id(), (int) $id, $reason),
                'Venue rejected. The owner has been notified with the reason.');
        }
        $this->redirect('/admin/venues');
    }

    public function forceDeactivate(string $id): void
    {
        $this->verifyCsrf();
        // TODO: deactivate the venue and cancel its future bookings and coaching sessions.
        Session::flash('info', 'Venue deactivation is not built yet.');
        $this->redirect('/admin/venues/' . (int) $id);
    }

    /** The current owner's venue, or the 404 page. */
    private function ownVenue(int $venueId): array
    {
        $venue = (new VenueService())->ownerVenue(Auth::id(), $venueId);
        if ($venue === null) {
            $this->notFound();
        }
        return $venue;
    }

    private function venueForm(string $view, string $title, array $data): void
    {
        $this->view($view, $data + [
            'title'      => $title,
            'sportTypes' => (new VenueService())->sportTypes(),
            'errors'     => [],
        ], 'dashboard');
    }

    /** Trimmed venue fields and their format errors. Business rules are checked by VenueService. */
    private function venueInput(): array
    {
        $data = [];
        foreach (['name', 'city', 'address', 'contact_phone', 'description'] as $field) {
            $value = $this->request->input($field);
            $data[$field] = is_string($value) ? trim($value) : '';
        }
        $sports = $this->request->input('sport_type_ids');
        $data['sport_type_ids'] = is_array($sports) ? array_values(array_filter($sports, 'is_scalar')) : [];

        $errors = (new Validator($data))
            ->required('name', 'Venue name')->maxLength('name', 150)
            ->required('city', 'City')->maxLength('city', 100)
            ->required('address', 'Address')->maxLength('address', 255)
            ->required('contact_phone', 'Contact phone')->maxLength('contact_phone', 20)
            ->maxLength('description', 2000)
            ->errors();
        if (!isset($errors['contact_phone']) && !preg_match('/^\+?[0-9 ]{9,15}$/', $data['contact_phone'])) {
            $errors['contact_phone'] = 'Enter a valid contact number.';
        }
        if ($data['sport_type_ids'] === []) {
            $errors['sport_type_ids'] = 'Choose at least one sport type.';
        }
        $data['description'] = $data['description'] === '' ? null : $data['description'];
        return [$data, $errors];
    }

    private function switchVenue(array $venue, callable $action, string $success): void
    {
        try {
            $action(new VenueService());
            Session::flash('success', $success);
        } catch (ValidationException $e) {
            Session::flash('error', $e->getMessage());
        }
        $this->redirect('/owner/venues/' . $venue['id']);
    }

    private function adminDecision(int $venueId, callable $action, string $success): void
    {
        $service = new VenueService();
        if ($service->adminVenue($venueId) === null) {
            $this->notFound();
        }
        try {
            $action($service);
            Session::flash('success', $success);
        } catch (ValidationException $e) {
            Session::flash('error', $e->getMessage());
        }
        $this->redirect('/admin/venues');
    }

    private function reasonInput(): ?string
    {
        $reason = $this->request->input('reason');
        $reason = is_string($reason) ? trim($reason) : '';
        if ($reason === '') {
            Session::flash('error', 'A reason is required.');
            return null;
        }
        if (mb_strlen($reason) > 500) {
            Session::flash('error', 'The reason must be at most 500 characters.');
            return null;
        }
        return $reason;
    }
}
