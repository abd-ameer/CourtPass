<?php
class ResaleController extends Controller
{
    public function index(): void
    {
        $this->view('customer/released-bookings', ['title' => 'Released Bookings'], 'dashboard');
    }

    public function release(string $id): void
    {
        $this->verifyCsrf();
        // TODO: release an online-paid confirmed booking; a cash booking is converted to online payment first.
        Session::flash('info', 'Releasing bookings is not built yet.');
        $this->redirect('/customer/bookings/' . (int) $id);
    }

    public function takeBack(string $id): void
    {
        $this->verifyCsrf();
        // TODO: return a released booking to confirmed while no one has rebooked the slot.
        Session::flash('info', 'Taking back a release is not built yet.');
        $this->redirect('/customer/bookings/' . (int) $id);
    }

    public function adminIndex(): void
    {
        $this->view('admin/disputes-resale', ['title' => 'Resale Disputes'], 'dashboard');
    }

    public function resolve(string $id): void
    {
        $this->verifyCsrf();
        // TODO: uphold or dismiss the resale dispute.
        Session::flash('info', 'Resale dispute resolution is not built yet.');
        $this->redirect('/admin/disputes/resale');
    }
}
