<?php
class FlashSlotController extends Controller
{
    public function index(): void
    {
        $this->view('owner/flash-slots', ['title' => 'Flash Deals'], 'dashboard');
    }

    public function store(): void
    {
        $this->verifyCsrf();
        // TODO: mark an unbooked, unblocked, non-coaching slot as a flash deal below the court rate.
        Session::flash('info', 'Flash deals are not built yet.');
        $this->redirect('/owner/flash-slots');
    }
}
