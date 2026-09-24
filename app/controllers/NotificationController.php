<?php
class NotificationController extends Controller
{
    public function index(): void
    {
        $this->view(Auth::role() . '/notifications', ['title' => 'Notifications'], 'dashboard');
    }

    public function markAllRead(): void
    {
        $this->verifyCsrf();
        // TODO: mark the user's notifications as read.
        Session::flash('info', 'Notifications are not built yet.');
        $this->redirect('/notifications');
    }
}
