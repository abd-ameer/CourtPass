<?php
class CheckInController extends Controller
{
    public function index(): void
    {
        $this->view('owner/check-in', [
            'title'  => 'Customer Check-in',
            'search' => trim((string) $this->request->query('q', '')),
        ], 'dashboard');
    }

    public function store(string $id): void
    {
        $this->verifyCsrf();
        // TODO: record the check-in for a confirmed booking at the owner's own venue.
        Session::flash('info', 'Check-in is not built yet.');
        $this->redirect('/owner/check-in');
    }
}
