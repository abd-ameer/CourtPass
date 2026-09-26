<?php
class CoachSessionController extends Controller
{
    public const CANCEL_REASONS = [
        'Personal or health reasons',
        'Venue unavailable',
        'Not enough registrations',
    ];

    public function index(): void
    {
        $status = (string) $this->request->query('status', '');
        $status = in_array($status, CoachSessionService::STATUS_FILTERS, true) ? $status : '';
        $venueId = (int) $this->request->query('venue', 0);

        $this->view('coach/sessions', [
            'title'   => 'My Sessions',
            'status'  => $status,
            'venueId' => $venueId,
        ] + (new CoachSessionService())->coachSessions(Auth::id(), $status, $venueId > 0 ? $venueId : null), 'dashboard');
    }

    public function create(): void
    {
        $this->createForm(['visibility' => 'public', 'capacity' => '8', 'fee' => '1500'], []);
    }

    public function store(): void
    {
        $this->verifyCsrf();
        $data = $this->sessionInput(['court_id', 'session_date', 'start_time', 'title', 'description', 'capacity', 'fee', 'visibility']);
        $errors = $this->detailErrors($data) + (new Validator($data))
            ->required('court_id', 'Court')->integer('court_id', 1)
            ->required('session_date', 'Date')->date('session_date')
            ->required('start_time', 'Start time')->hour('start_time')
            ->required('visibility', 'Visibility')->in('visibility', ['public', 'private'])
            ->errors();
        if (isset($errors['start_time'])) {
            $errors['slot'] = $errors['start_time'];
        }

        if ($errors === []) {
            try {
                $id = (new CoachSessionService())->create(Auth::id(), $data);
                Session::flash('success', $data['visibility'] === 'private'
                    ? 'Private session created. Share its link from this page.'
                    : 'Session created. It is listed on the Coaching page and its court slot is blocked.');
                $this->redirect('/coach/sessions/' . $id);
            } catch (ValidationException $e) {
                $errors = $e->errors();
            }
        }
        http_response_code(422);
        $this->createForm($data, $errors);
    }

    public function show(string $id): void
    {
        $session = $this->ownSession((int) $id);
        $this->view('coach/session-details', ['title' => $session['title'], 'session' => $session], 'dashboard');
    }

    public function edit(string $id): void
    {
        $session = $this->ownSession((int) $id);
        if (!$session['can_edit']) {
            Session::flash('info', 'Only upcoming sessions can be edited.');
            $this->redirect('/coach/sessions/' . $session['id']);
        }
        $this->editForm($session, $session, []);
    }

    public function update(string $id): void
    {
        $this->verifyCsrf();
        $session = $this->ownSession((int) $id);
        $data = $this->sessionInput(['title', 'description', 'capacity', 'fee']);
        $errors = $this->detailErrors($data);

        if ($errors === []) {
            try {
                (new CoachSessionService())->update(Auth::id(), $session['id'], $data);
                Session::flash('success', 'Session details saved.');
                $this->redirect('/coach/sessions/' . $session['id']);
            } catch (ValidationException $e) {
                if (isset($e->errors()['session'])) {
                    Session::flash('error', $e->getMessage());
                    $this->redirect('/coach/sessions/' . $session['id']);
                }
                $errors = $e->errors();
            }
        }
        http_response_code(422);
        $this->editForm($session, $data, $errors);
    }

    public function confirmCancel(string $id): void
    {
        $session = $this->ownSession((int) $id);
        if (!$session['can_cancel']) {
            Session::flash('info', 'Only upcoming sessions can be cancelled.');
            $this->redirect('/coach/sessions/' . $session['id']);
        }
        $this->cancelForm($session, ['reason_choice' => '', 'reason_text' => ''], []);
    }

    public function cancel(string $id): void
    {
        $this->verifyCsrf();
        $session = $this->ownSession((int) $id);
        $old = $this->sessionInput(['reason_choice', 'reason_text']);
        $reason = $this->reasonInput($old, $errors);

        if ($reason !== null) {
            try {
                $result = (new CoachSessionService())->cancel(Auth::id(), $session['id'], $reason);
                $message = 'Session cancelled and its court slot released.';
                if ($result['registrations'] > 0) {
                    $message .= " {$result['registrations']} registration(s) cancelled"
                        . ($result['refund_total'] > 0 ? ' with full refunds totalling ' . lkr($result['refund_total']) : '')
                        . ' and the customers notified.';
                }
                Session::flash('success', $message);
                $this->redirect('/coach/sessions/' . $session['id']);
            } catch (ValidationException $e) {
                if (isset($e->errors()['session'])) {
                    Session::flash('error', $e->getMessage());
                    $this->redirect('/coach/sessions/' . $session['id']);
                }
                $errors = ['reason_text' => $e->errors()['reason'] ?? $e->getMessage()];
            }
        }
        http_response_code(422);
        $this->cancelForm($session, $old, $errors);
    }

    public function registrations(string $id): void
    {
        $session = $this->ownSession((int) $id);
        // TODO: registration list and payment status from the registration service (sample rows for the interim).
        $this->view('coach/session-registrations', ['title' => 'Registrations', 'sessionId' => $session['id']], 'dashboard');
    }

    public function attendance(string $id): void
    {
        $session = $this->ownSession((int) $id);
        $this->view('coach/attendance', ['title' => 'Attendance', 'sessionId' => $session['id']], 'dashboard');
    }

    public function saveAttendance(string $id): void
    {
        $this->verifyCsrf();
        // TODO: mark attended or absent after the start time; marks can be corrected for 24 hours.
        Session::flash('info', 'Attendance marking is not built yet.');
        $this->redirect('/coach/sessions/' . (int) $id . '/attendance');
    }

    /** The current coach's session, or the 404 page. */
    private function ownSession(int $sessionId): array
    {
        $session = (new CoachSessionService())->coachSession(Auth::id(), $sessionId);
        if ($session === null) {
            $this->notFound();
        }
        return $session;
    }

    /** Trimmed string inputs; anything that is not a string becomes ''. */
    private function sessionInput(array $fields): array
    {
        $data = [];
        foreach ($fields as $field) {
            $value = $this->request->input($field);
            $data[$field] = is_string($value) ? trim($value) : '';
        }
        return $data;
    }

    /** Format checks shared by create and edit. Business rules are checked again by CoachSessionService. */
    private function detailErrors(array $data): array
    {
        $errors = (new Validator($data))
            ->required('title', 'Title')->maxLength('title', 100)
            ->required('description', 'Description')->maxLength('description', 2000)
            ->required('capacity', 'Capacity')->integer('capacity', 1, CoachSessionService::MAX_CAPACITY)
            ->required('fee', 'Fee')->decimal('fee', 0, CoachSessionService::MAX_FEE)
            ->errors();
        if (isset($errors['capacity']) && $data['capacity'] !== '') {
            $errors['capacity'] = 'Capacity must be a whole number from 1 to ' . CoachSessionService::MAX_CAPACITY . '.';
        }
        if (isset($errors['fee']) && $data['fee'] !== '') {
            $errors['fee'] = 'Enter a fee from LKR 0 (free) to ' . lkr(CoachSessionService::MAX_FEE) . ', with at most two decimals.';
        }
        return $errors;
    }

    /** The cancel reason: one of CANCEL_REASONS, or the typed text when Other is chosen. Null with $errors set when missing. */
    private function reasonInput(array $old, ?array &$errors): ?string
    {
        $errors = [];
        if ($old['reason_choice'] === 'other') {
            if ($old['reason_text'] === '') {
                $errors['reason_text'] = 'Type the reason for cancelling.';
            } elseif (mb_strlen($old['reason_text']) > 500) {
                $errors['reason_text'] = 'The reason must be at most 500 characters.';
            }
            return $errors === [] ? $old['reason_text'] : null;
        }
        if (!in_array($old['reason_choice'], self::CANCEL_REASONS, true)) {
            $errors['reason_choice'] = 'Choose a reason for cancelling.';
            return null;
        }
        return $old['reason_choice'];
    }

    private function createForm(array $old, array $errors): void
    {
        $this->view('coach/create-session', [
            'title'      => 'Create Session',
            'venues'     => (new CoachSessionService())->sessionVenues(Auth::id()),
            'old'        => $old,
            'errors'     => $errors,
            'daysAhead'  => BookingService::DAYS_AHEAD,
            'maxCapacity' => CoachSessionService::MAX_CAPACITY,
            'maxFee'     => CoachSessionService::MAX_FEE,
        ], 'dashboard');
    }

    private function editForm(array $session, array $old, array $errors): void
    {
        $this->view('coach/edit-session', [
            'title'       => 'Edit Session',
            'session'     => $session,
            'old'         => $old,
            'errors'      => $errors,
            'maxCapacity' => CoachSessionService::MAX_CAPACITY,
            'maxFee'      => CoachSessionService::MAX_FEE,
        ], 'dashboard');
    }

    private function cancelForm(array $session, array $old, array $errors): void
    {
        $this->view('coach/cancel-session', [
            'title'   => 'Cancel Session',
            'session' => $session,
            'reasons' => self::CANCEL_REASONS,
            'old'     => $old,
            'errors'  => $errors,
        ], 'dashboard');
    }
}
