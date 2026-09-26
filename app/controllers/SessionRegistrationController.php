<?php
class SessionRegistrationController extends Controller
{
    public function show(string $id): void
    {
        $session = (new CoachSessionService())->publicSession((int) $id);
        if ($session === null) {
            $this->notFound();
        }
        $this->view('public/session-details', ['title' => $session['title'], 'session' => $session]);
    }

    public function showPrivate(string $token): void
    {
        $session = (new CoachSessionService())->privateSession($token);
        if ($session === null) {
            $this->notFound();
        }
        $this->view('public/session-details', ['title' => $session['title'], 'session' => $session]);
    }

    public function store(string $id): void
    {
        $this->verifyCsrf();
        if ((new CoachSessionService())->publicSession((int) $id) === null) {
            $this->notFound();
        }
        // TODO: capacity check, create the pending_payment registration and redirect to PayHere.
        Session::flash('info', 'Registration with online payment opens when PayHere payments are connected.');
        $this->redirect('/sessions/' . (int) $id);
    }

    public function index(): void
    {
        $this->view('customer/my-sessions', ['title' => 'My Sessions'], 'dashboard');
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
        $this->view('customer/create-review', [
            'title'          => 'Review Coach',
            'target'         => 'coach',
            'registrationId' => (int) $id,
            'bookingId'      => null,
            'reviewId'       => null,
        ], 'dashboard');
    }

    public function storeReview(string $id): void
    {
        $this->verifyCsrf();
        // TODO: only an attended registration, within 7 days, once per registration.
        Session::flash('info', 'Coach reviews are not built yet.');
        $this->redirect('/customer/reviews');
    }
}
