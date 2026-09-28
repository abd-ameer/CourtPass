<?php
class SessionRegistrationController extends Controller
{
    public function show(string $id): void
    {
        $session = (new CoachSessionService())->publicSession((int) $id);
        if ($session === null) {
            $this->notFound();
        }
        $this->view('public/session-details', ['title' => $session['title'], 'session' => $session, 'mine' => $this->myRegistration($session['id'])]);
    }

    public function showPrivate(string $token): void
    {
        $session = (new CoachSessionService())->privateSession($token);
        if ($session === null) {
            $this->notFound();
        }
        $this->view('public/session-details', ['title' => $session['title'], 'session' => $session, 'mine' => $this->myRegistration($session['id'])]);
    }

    public function store(string $id): void
    {
        $this->verifyCsrf();
        if ((new CoachSessionService())->publicSession((int) $id) === null) {
            $this->notFound();
        }
        // TODO: capacity check, create the pending_payment registration and redirect to PayHere.
        Session::flash('info', 'Registering for coaching sessions is not available yet.');
        $this->redirect('/sessions/' . (int) $id);
    }

    public function index(): void
    {
        $this->view('customer/my-sessions', [
            'title'         => 'My Sessions',
            'registrations' => (new CoachSessionService())->customerRegistrations(Auth::id()),
        ], 'dashboard');
    }

    public function cancel(string $id): void
    {
        $this->verifyCsrf();
        // TODO: cancel the customer's own registration with the 100 / 50 / 0 refund tiers.
        Session::flash('info', 'Cancelling registrations is not built yet.');
        $this->redirect('/customer/sessions');
    }

    public function createReview(string $id): void
    {
        $registration = $this->ownRegistration((int) $id);
        if ($registration === null || $registration['status'] !== 'attended') {
            $this->notFound();
        }
        if (!$registration['can_review']) {
            Session::flash('info', $registration['review_id'] !== null
                ? 'You have already reviewed this session. Your review is on My Reviews.'
                : 'The 7-day review window for this session has closed.');
            $this->redirect($registration['review_id'] !== null ? '/customer/reviews' : '/customer/sessions');
        }
        $this->view('customer/create-review', [
            'title'          => 'Review Coach',
            'target'         => 'coach',
            'registrationId' => (int) $id,
            'bookingId'      => null,
            'reviewId'       => null,
            'subject'        => 'Coach ' . $registration['coach_name'] . ' (' . $registration['title'] . ')',
            'rating'         => 5,
            'comment'        => '',
            'reviewUntil'    => $registration['review_until'],
        ], 'dashboard');
    }

    public function storeReview(string $id): void
    {
        $this->verifyCsrf();
        // TODO: only an attended registration, within 7 days, once per registration.
        Session::flash('info', 'Coach reviews are not built yet.');
        $this->redirect('/customer/reviews');
    }

    /** The logged-in customer's registration by id, or null. */
    private function ownRegistration(int $registrationId): ?array
    {
        if (!Auth::hasRole('customer')) {
            return null;
        }
        foreach ((new CoachSessionService())->customerRegistrations(Auth::id()) as $r) {
            if ($r['id'] === $registrationId) {
                return $r;
            }
        }
        return null;
    }

    /** The logged-in customer's live or attended registration for a session, or null. */
    private function myRegistration(int $sessionId): ?array
    {
        if (!Auth::hasRole('customer')) {
            return null;
        }
        foreach ((new CoachSessionService())->customerRegistrations(Auth::id()) as $r) {
            if ($r['session_id'] === $sessionId && $r['status'] !== 'cancelled') {
                return $r;
            }
        }
        return null;
    }
}
