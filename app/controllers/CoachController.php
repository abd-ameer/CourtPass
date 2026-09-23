<?php
class CoachController extends Controller
{
    private function show(string $view, string $title, array $data = []): void
    {
        $this->view('coach/' . $view, array_merge(['title' => $title, 'active_role' => 'coach'], $data), 'dashboard');
    }

    public function dashboard(): void
    {
        $this->show('dashboard', 'Coach Dashboard');
    }

    public function sessions(): void
    {
        $this->show('sessions', 'My Coaching Sessions');
    }

    public function createSession(): void
    {
        $this->show('create-session', 'Publish New Coaching Session');
    }

    public function editSession(): void
    {
        $id = $this->request->query('id', 'session-101');
        $this->show('edit-session', 'Edit Coaching Session', ['sessionId' => $id]);
    }

    public function cancelSession(): void
    {
        $id = $this->request->query('id', 'session-101');
        $this->show('cancel-session', 'Cancel Coaching Session', ['sessionId' => $id]);
    }

    public function sessionDetails(): void
    {
        $id = $this->request->query('id', 'session-101');
        $this->show('session-details', 'Session Overview', ['sessionId' => $id]);
    }

    public function sessionRegistrations(): void
    {
        $id = $this->request->query('id', 'session-101');
        $this->show('session-registrations', 'Student Registrations', ['sessionId' => $id]);
    }

    public function attendance(): void
    {
        $this->show('attendance', 'Attendance Desk');
    }

    public function venues(): void
    {
        $this->show('venues', 'Approved Partner Venues');
    }

    public function earnings(): void
    {
        $this->show('earnings', 'Coach Earnings & Settlements');
    }

    public function reviews(): void
    {
        $this->show('reviews', 'Reviews & Feedback');
    }

    public function notifications(): void
    {
        $this->show('notifications', 'Coach Notifications');
    }

    public function profile(): void
    {
        $this->show('profile', 'Coach Profile & Accreditations');
    }

    public function settings(): void
    {
        $this->show('settings', 'Coach Settings');
    }
}
