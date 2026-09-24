<?php
class AnnouncementController extends Controller
{
    public function index(): void
    {
        $this->view('owner/announcements', ['title' => 'Announcements'], 'dashboard');
    }

    public function store(): void
    {
        $this->verifyCsrf();
        // TODO: post an operational or promotional announcement for the owner's own venue.
        Session::flash('info', 'Announcements are not built yet.');
        $this->redirect('/owner/announcements');
    }

    public function moderation(): void
    {
        $this->view('admin/announcement-moderation', ['title' => 'Announcement Moderation'], 'dashboard');
    }

    public function remove(string $id): void
    {
        $this->verifyCsrf();
        // TODO: set the announcement status to removed.
        Session::flash('info', 'Announcement removal is not built yet.');
        $this->redirect('/admin/announcements');
    }
}
