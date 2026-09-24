<?php
class CoachController extends Controller
{
    private const EXPERIENCE_LEVELS = ['beginner', 'intermediate', 'advanced', 'professional'];

    public function coachForm(): void
    {
        $this->view('auth/register-coach', $this->formData([], []));
    }

    public function registerCoach(): void
    {
        $this->verifyCsrf();
        $data = $this->request->all();
        $errors = AuthController::accountErrors($data);

        $v = (new Validator($data))
            ->required('experience_level', 'Experience level')->in('experience_level', self::EXPERIENCE_LEVELS)
            ->required('bio', 'Short bio')->maxLength('bio', 1000);
        $errors += $v->errors();

        $sportIds = array_values(array_unique(array_map('intval', (array) ($data['sports'] ?? []))));
        $validIds = array_column($this->sportTypes(), 'id');
        if ($sportIds === [] || array_diff($sportIds, $validIds) !== []) {
            $errors['sports'] = 'Choose at least one sport you coach.';
        }

        $old = AuthController::accountOld($data) + [
            'experience_level' => (string) ($data['experience_level'] ?? ''),
            'bio'              => trim((string) ($data['bio'] ?? '')),
            'sports'           => $sportIds,
        ];

        if ($errors !== []) {
            http_response_code(422);
            $this->view('auth/register-coach', $this->formData($old, $errors));
            return;
        }

        // TODO: create the users row (role coach) through the auth service, then
        // coach_profiles and coach_sports in the same transaction; log in and redirect.
        Session::flash('info', 'Sign-up is not connected to the database yet.');
        $this->view('auth/register-coach', $this->formData($old, []));
    }

    public function dashboard(): void
    {
        $this->view('coach/dashboard', ['title' => 'Coach Dashboard'], 'dashboard');
    }

    public function editProfile(): void
    {
        // TODO: load coach_profiles and coach_sports for Auth::id().
        $profile = [
            'bio' => 'Former national-level badminton player. Coaching juniors and adults for 8 years.',
            'experience_level' => 'professional', 'certifications' => 'BWF Level 1 Coach', 'sport_type_ids' => [2, 3],
        ];
        $this->view('coach/profile', [
            'title'            => 'Coach Profile',
            'profile'          => $profile,
            'sportTypes'       => $this->sportTypes(),
            'experienceLevels' => self::EXPERIENCE_LEVELS,
        ], 'dashboard');
    }

    public function updateProfile(): void
    {
        $this->verifyCsrf();
        // TODO: update bio, sport types, experience level and certifications.
        Session::flash('info', 'Profile editing is not built yet.');
        $this->redirect('/coach/profile');
    }

    public function earnings(): void
    {
        $this->view('coach/earnings', ['title' => 'Earnings'], 'dashboard');
    }

    public function reviews(): void
    {
        $this->view('coach/reviews', ['title' => 'My Reviews'], 'dashboard');
    }

    public function respond(string $id): void
    {
        $this->verifyCsrf();
        // TODO: one public response per review; reject a second one.
        Session::flash('info', 'Review responses are not built yet.');
        $this->redirect('/coach/reviews');
    }

    public function venues(): void
    {
        $this->view('coach/venues', ['title' => 'Venue Approvals'], 'dashboard');
    }

    public function requestVenue(): void
    {
        $this->verifyCsrf();
        // TODO: request approval at an approved, active venue.
        Session::flash('info', 'Venue requests are not built yet.');
        $this->redirect('/coach/venues');
    }

    public function ownerRequests(): void
    {
        $this->view('owner/coach-requests', ['title' => 'Coach Requests'], 'dashboard');
    }

    public function approveRequest(string $id): void
    {
        $this->verifyCsrf();
        // TODO: approve a pending request at the owner's own venue.
        Session::flash('info', 'Coach approvals are not built yet.');
        $this->redirect('/owner/coach-requests');
    }

    public function declineRequest(string $id): void
    {
        $this->verifyCsrf();
        // TODO: decline a pending request; the reason is mandatory.
        Session::flash('info', 'Coach approvals are not built yet.');
        $this->redirect('/owner/coach-requests');
    }

    public function revokeApproval(string $id): void
    {
        $this->verifyCsrf();
        // TODO: revoke an approval; existing sessions stay.
        Session::flash('info', 'Coach approvals are not built yet.');
        $this->redirect('/owner/coach-requests');
    }

    public function coaching(): void
    {
        // TODO: upcoming public sessions filtered by sport, venue and date from the coach session service.
        $sessions = [
            [
                'id' => 2, 'title' => 'Intermediate Rally Drills', 'sport' => 'Badminton',
                'venue_name' => 'Colombo Sports Hub', 'court_name' => 'Badminton Court 2',
                'coach_id' => 7, 'coach_name' => 'Ashan Weerasinghe', 'coach_verified' => true, 'coach_rating' => 4.0,
                'starts_at' => '2026-09-29 08:00:00', 'fee' => 1800.00, 'capacity' => 4, 'registered_count' => 1,
                'status' => 'open',
            ],
        ];

        $this->view('public/coaching', [
            'title'    => 'Coaching Sessions',
            'sessions' => $sessions,
            'sports'   => array_map(fn ($s) => ['code' => $s['code'], 'name' => $s['name']], $this->sportTypes()),
            'venues'   => [['id' => 1, 'name' => 'Colombo Sports Hub'], ['id' => 2, 'name' => 'Kandy Court Zone']],
            'filters'  => [
                'sport' => (string) $this->request->query('sport', ''),
                'venue' => (int) $this->request->query('venue', 0),
                'date'  => (string) $this->request->query('date', ''),
            ],
        ]);
    }

    public function publicProfile(string $id): void
    {
        $this->view('public/coach-profile', ['title' => 'Coach Profile', 'coachId' => (int) $id]);
    }

    public function adminIndex(): void
    {
        $this->view('admin/coach-verifications', ['title' => 'Coach Verification'], 'dashboard');
    }

    public function verify(string $id): void
    {
        $this->verifyCsrf();
        // TODO: mark the coach as verified after offline checks.
        Session::flash('info', 'Coach verification is not built yet.');
        $this->redirect('/admin/coaches');
    }

    private function formData(array $old, array $errors): array
    {
        return [
            'title'            => 'Coach Sign Up',
            'old'              => $old,
            'errors'           => $errors,
            'sportTypes'       => $this->sportTypes(),
            'experienceLevels' => self::EXPERIENCE_LEVELS,
        ];
    }

    private function sportTypes(): array
    {
        // TODO: read from sport_types.
        return [
            ['id' => 1, 'code' => 'futsal', 'name' => 'Futsal'],
            ['id' => 2, 'code' => 'badminton', 'name' => 'Badminton'],
            ['id' => 3, 'code' => 'pickleball', 'name' => 'Pickleball'],
            ['id' => 4, 'code' => 'squash', 'name' => 'Squash'],
            ['id' => 5, 'code' => 'billiards', 'name' => 'Billiards'],
            ['id' => 6, 'code' => 'carrom', 'name' => 'Carrom'],
            ['id' => 7, 'code' => 'table_tennis', 'name' => 'Table Tennis'],
        ];
    }
}
