<?php
class AdminUserController extends Controller
{
    public function index(): void
    {
        $this->view('admin/users', [
            'title' => 'User Management',
            'role'  => (string) $this->request->query('role', ''),
        ], 'dashboard');
    }

    public function show(string $id): void
    {
        $this->view('admin/user-details', ['title' => 'User Details', 'userId' => (int) $id], 'dashboard');
    }

    public function deactivate(string $id): void
    {
        $this->verifyCsrf();
        // TODO: deactivate the account; a coach also has future sessions cancelled with full refunds.
        Session::flash('info', 'Account deactivation is not built yet.');
        $this->redirect('/admin/users/' . (int) $id);
    }
}
