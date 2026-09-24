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
        $this->view('owner/venues', ['title' => 'My Venues'], 'dashboard');
    }

    public function create(): void
    {
        $this->view('owner/add-venue', [
            'title'      => 'Register a Venue',
            'venue'      => [],
            'sportTypes' => $this->sportTypes(),
            'errors'     => [],
        ], 'dashboard');
    }

    public function store(): void
    {
        $this->verifyCsrf();
        // TODO: validate and create the venue in the pending state.
        Session::flash('info', 'Venue registration is not built yet.');
        $this->redirect('/owner/venues');
    }

    public function show(string $id): void
    {
        $this->view('owner/venue-details', ['title' => 'Venue Details', 'venueId' => (int) $id], 'dashboard');
    }

    public function edit(string $id): void
    {
        // TODO: load the owner's own venue; 404 when it belongs to someone else.
        $venue = [
            'name' => 'Colombo Sports Hub', 'city' => 'Colombo', 'contact_phone' => '0112345678',
            'address' => '45 Galle Road, Colombo 03',
            'description' => 'Indoor futsal, badminton and table tennis courts. Air-conditioned, open 7 days.', 'sport_type_ids' => [1, 2, 7],
        ];
        $this->view('owner/edit-venue', [
            'title'      => 'Edit Venue',
            'venueId'    => (int) $id,
            'venue'      => $venue,
            'sportTypes' => $this->sportTypes(),
            'errors'     => [],
        ], 'dashboard');
    }

    public function update(string $id): void
    {
        $this->verifyCsrf();
        // TODO: validate and update the owner's own venue.
        Session::flash('info', 'Venue editing is not built yet.');
        $this->redirect('/owner/venues/' . (int) $id);
    }

    public function confirmDeactivate(string $id): void
    {
        $this->view('owner/deactivate-venue', ['title' => 'Deactivate Venue', 'venueId' => (int) $id], 'dashboard');
    }

    public function deactivate(string $id): void
    {
        $this->verifyCsrf();
        // TODO: deactivate the owner's own venue (existing bookings and sessions are kept).
        Session::flash('info', 'Venue deactivation is not built yet.');
        $this->redirect('/owner/venues');
    }

    public function adminDashboard(): void
    {
        $this->view('admin/dashboard', ['title' => 'Admin Dashboard'], 'dashboard');
    }

    public function adminIndex(): void
    {
        $this->view('admin/venue-approvals', ['title' => 'Venue Approvals'], 'dashboard');
    }

    public function adminShow(string $id): void
    {
        $this->view('admin/venue-details', ['title' => 'Venue Review', 'venueId' => (int) $id], 'dashboard');
    }

    public function approve(string $id): void
    {
        $this->verifyCsrf();
        // TODO: approve a pending venue.
        Session::flash('info', 'Venue approval is not built yet.');
        $this->redirect('/admin/venues');
    }

    public function reject(string $id): void
    {
        $this->verifyCsrf();
        // TODO: reject a pending venue; the reason is mandatory.
        Session::flash('info', 'Venue rejection is not built yet.');
        $this->redirect('/admin/venues');
    }

    public function forceDeactivate(string $id): void
    {
        $this->verifyCsrf();
        // TODO: deactivate the venue and cancel its future bookings and coaching sessions.
        Session::flash('info', 'Venue deactivation is not built yet.');
        $this->redirect('/admin/venues/' . (int) $id);
    }

    private function sportTypes(): array
    {
        return (new VenueService())->sportTypes();
    }
}
