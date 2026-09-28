<?php
class NotificationController extends Controller
{
    public function index(): void
    {
        $this->view('notifications/index', [
            'title' => 'Notifications',
            'items' => (new NotificationService())->recent(Auth::id()),
        ], 'dashboard');
    }

    public function markAllRead(): void
    {
        $this->verifyCsrf();
        $count = (new NotificationService())->markAllRead(Auth::id());
        Session::flash('success', $count === 0 ? 'You have no unread notifications.' : 'All notifications are marked as read.');
        $this->redirect('/notifications');
    }
}
