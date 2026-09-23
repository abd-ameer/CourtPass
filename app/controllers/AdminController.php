<?php
class AdminController extends Controller
{
    private function show(string $view, string $title, array $data = []): void
    {
        $this->view('admin/' . $view, array_merge(['title' => $title, 'active_role' => 'admin'], $data), 'dashboard');
    }

    public function dashboard(): void
    {
        $this->show('dashboard', 'Admin Control Panel');
    }

    public function users(): void
    {
        $this->show('users', 'User Account Management');
    }

    public function userDetails(): void
    {
        $id = $this->request->query('id', 'usr-101');
        $this->show('user-details', 'User Account Details', ['userId' => $id]);
    }

    public function venueApprovals(): void
    {
        $this->show('venue-approvals', 'Pending Venue Approvals');
    }

    public function venueDetails(): void
    {
        $id = $this->request->query('id', 'venue-1');
        $this->show('venue-details', 'Admin Venue Audit', ['venueId' => $id]);
    }

    public function coachVerifications(): void
    {
        $this->show('coach-verifications', 'Coach Verification Desk');
    }

    public function disputesNoShow(): void
    {
        $this->show('disputes-no-show', 'No-Show Reliability Disputes');
    }

    public function disputesResale(): void
    {
        $this->show('disputes-resale', 'Resale Price Cap Disputes');
    }

    public function reviewModeration(): void
    {
        $this->show('review-moderation', 'Review Moderation');
    }

    public function announcementModeration(): void
    {
        $this->show('announcement-moderation', 'Announcement Moderation');
    }

    public function notifications(): void
    {
        $this->show('notifications', 'System Notifications');
    }

    public function settings(): void
    {
        $this->show('settings', 'Platform Settings');
    }
}
