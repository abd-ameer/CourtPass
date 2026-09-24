<?php
class CoachSessionController extends Controller
{
    public function index(): void
    {
        $this->view('coach/sessions', [
            'title'  => 'My Sessions',
            'status' => (string) $this->request->query('status', ''),
        ], 'dashboard');
    }

    public function create(): void
    {
        $this->view('coach/create-session', ['title' => 'Create Session'], 'dashboard');
    }

    public function store(): void
    {
        $this->verifyCsrf();
        // TODO: approved venue check, conflict check, court block and session insert in one transaction.
        Session::flash('info', 'Creating sessions is not built yet.');
        $this->redirect('/coach/sessions');
    }

    public function show(string $id): void
    {
        $this->view('coach/session-details', ['title' => 'Session Details', 'sessionId' => (int) $id], 'dashboard');
    }

    public function edit(string $id): void
    {
        $this->view('coach/edit-session', ['title' => 'Edit Session', 'sessionId' => (int) $id], 'dashboard');
    }

    public function update(string $id): void
    {
        $this->verifyCsrf();
        // TODO: edit title, description, capacity (not below registrations) and fee (before any registration).
        Session::flash('info', 'Editing sessions is not built yet.');
        $this->redirect('/coach/sessions/' . (int) $id);
    }

    public function confirmCancel(string $id): void
    {
        $this->view('coach/cancel-session', ['title' => 'Cancel Session', 'sessionId' => (int) $id], 'dashboard');
    }

    public function cancel(string $id): void
    {
        $this->verifyCsrf();
        // TODO: cancel, refund every registration in full and release the court block.
        Session::flash('info', 'Cancelling sessions is not built yet.');
        $this->redirect('/coach/sessions');
    }

    public function registrations(string $id): void
    {
        $this->view('coach/session-registrations', ['title' => 'Registrations', 'sessionId' => (int) $id], 'dashboard');
    }

    public function attendance(string $id): void
    {
        $this->view('coach/attendance', ['title' => 'Attendance', 'sessionId' => (int) $id], 'dashboard');
    }

    public function saveAttendance(string $id): void
    {
        $this->verifyCsrf();
        // TODO: mark attended or absent after the start time; marks can be corrected for 24 hours.
        Session::flash('info', 'Attendance marking is not built yet.');
        $this->redirect('/coach/sessions/' . (int) $id . '/attendance');
    }
}
