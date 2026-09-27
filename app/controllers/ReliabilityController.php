<?php
class ReliabilityController extends Controller
{
    public function show(): void
    {
        $bookings = (new BookingService())->customerBookings(Auth::id());
        // Every booking row carries the customer's profile; with no bookings the profile still has its sign-up defaults.
        $profile = $bookings[0] ?? ['reliability_score' => null, 'reliability_tier' => 'new_member', 'completed_count' => 0, 'no_show_count' => 0];

        $this->view('customer/reliability', [
            'title'   => 'Reliability Score',
            'profile' => $profile,
            'noShows' => array_values(array_filter($bookings, fn (array $b) => $b['status'] === 'no_show')),
        ], 'dashboard');
    }

    public function storeDispute(): void
    {
        $this->verifyCsrf();
        // TODO: open a no-show dispute for one of the customer's own no_show bookings.
        Session::flash('info', 'No-show disputes are not built yet.');
        $this->redirect('/customer/reliability');
    }

    public function adminIndex(): void
    {
        $this->view('admin/disputes-no-show', ['title' => 'No-Show Disputes'], 'dashboard');
    }

    public function resolve(string $id): void
    {
        $this->verifyCsrf();
        // TODO: uphold or dismiss; clearing a penalty recalculates the reliability score.
        Session::flash('info', 'No-show dispute resolution is not built yet.');
        $this->redirect('/admin/disputes/no-show');
    }
}
