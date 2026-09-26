<?php
class CoachController extends Controller
{
    private const EXPERIENCE_LEVELS = ['beginner', 'intermediate', 'advanced', 'professional'];

    public function coachForm(): void
    {
        $this->redirectIfLoggedIn();
        $this->view('auth/register-coach', $this->formData([], []));
    }

    public function registerCoach(): void
    {
        $this->verifyCsrf();
        $this->redirectIfLoggedIn();
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

        try {
            $user = (new CoachService())->register(['sports' => $sportIds] + $data);
        } catch (ValidationException $e) {
            http_response_code(422);
            $this->view('auth/register-coach', $this->formData($old, $e->errors()));
            return;
        }

        Auth::login($user);
        Session::flash('success', 'Welcome to CourtPass, ' . $user['name'] . '! Your coach account is ready.');
        $this->redirect(Auth::homeUrl());
    }

    public function dashboard(): void
    {
        $service = new CoachSessionService();
        $this->view('coach/dashboard', [
            'title'     => 'Coach Dashboard',
            'coachName' => Auth::user()['name'],
            'upcoming'  => $service->coachSessions(Auth::id(), 'upcoming')['sessions'],
            'hasVenues' => $service->sessionVenues(Auth::id()) !== [],
        ], 'dashboard');
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
        $sports = array_map(fn ($s) => ['code' => $s['code'], 'name' => $s['name']], $this->sportTypes());
        $venues = array_map(fn ($v) => ['id' => $v['id'], 'name' => $v['name']], (new VenueService())->publicVenues());

        $sport = (string) $this->request->query('sport', '');
        $sport = in_array($sport, array_column($sports, 'code'), true) ? $sport : '';
        $venueId = (int) $this->request->query('venue', 0);
        $venueId = in_array($venueId, array_column($venues, 'id'), true) ? $venueId : 0;
        $date = (string) $this->request->query('date', '');
        $date = (new Validator(['date' => $date]))->date('date')->fails() ? '' : $date;

        $this->view('public/coaching', [
            'title'    => 'Coaching Sessions',
            'sessions' => (new CoachSessionService())->publicSessions($sport ?: null, $venueId ?: null, $date ?: null),
            'sports'   => $sports,
            'venues'   => $venues,
            'filters'  => ['sport' => $sport, 'venue' => $venueId, 'date' => $date],
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
        return (new VenueService())->sportTypes();
    }
}
