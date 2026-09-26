<?php
class CheckInController extends Controller
{
    public function index(): void
    {
        $desk = (new CheckInService())->desk(Auth::id(), $this->text('q'));
        $this->view('owner/check-in', ['title' => 'Customer Check-in', 'desk' => $desk], 'dashboard');
    }

    public function store(string $id): void
    {
        $this->verifyCsrf();
        try {
            $booking = (new CheckInService())->checkIn(Auth::id(), (int) $id);
            Session::flash('success', "{$booking['customer_name']} is checked in for booking #{$booking['id']} ({$booking['court_name']}, {$booking['start']} to {$booking['end']}).");
        } catch (ValidationException $e) {
            if (($e->errors()['booking'] ?? '') === 'Booking not found.') {
                $this->notFound();
            }
            Session::flash('error', $e->getMessage());
        }
        $search = trim($this->text('q'));
        $this->redirect('/owner/check-in' . ($search === '' ? '' : '?q=' . rawurlencode($search)));
    }

    /** A text query or form field; anything that is not a string counts as empty. */
    private function text(string $key, bool $fromQuery = true): string
    {
        $value = $fromQuery ? $this->request->query($key, '') : $this->request->input($key, '');
        return is_string($value) ? $value : '';
    }
}
