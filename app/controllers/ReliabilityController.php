<?php
class ReliabilityController extends Controller
{
    public function show(): void
    {
        $this->view('customer/reliability', ['title' => 'Reliability Score'], 'dashboard');
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
