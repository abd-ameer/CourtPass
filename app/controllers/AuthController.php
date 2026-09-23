<?php
class AuthController extends Controller
{
    private array $demoUsers = [
        'customer' => ['id' => 1, 'role' => 'customer', 'name' => 'Kasun Jayawardena', 'email' => 'kasun.j@gmail.com'],
        'owner'    => ['id' => 2, 'role' => 'owner',    'name' => 'Nuwan Senanayake',  'email' => 'nuwan@colombofutsal.lk'],
        'coach'    => ['id' => 3, 'role' => 'coach',    'name' => 'Coach Dilshan Perera', 'email' => 'dilshan@courtpass.lk'],
        'admin'    => ['id' => 4, 'role' => 'admin',    'name' => 'CourtPass Admin',   'email' => 'admin@courtpass.lk'],
    ];

    public function login(): void
    {
        if (Auth::check()) {
            Response::redirect(Auth::homeUrl());
            return;
        }

        $this->view('public/login', ['title' => 'Log In to CourtPass']);
    }

    public function demoLogin(): void
    {
        $role = $this->request->query('role', 'customer');
        $user = $this->demoUsers[$role] ?? $this->demoUsers['customer'];
        Auth::login($user);
        Session::flash('success', "Logged in as {$user['name']} (" . ucfirst($user['role']) . ").");
        Response::redirect(Auth::homeUrl());
    }

    public function loginSubmit(): void
    {
        $email = strtolower(trim((string) $this->request->input('email', '')));
        
        $role = 'customer';
        if (str_contains($email, 'owner') || str_contains($email, 'futsal')) {
            $role = 'owner';
        } elseif (str_contains($email, 'coach') || str_contains($email, 'dilshan')) {
            $role = 'coach';
        } elseif (str_contains($email, 'admin')) {
            $role = 'admin';
        }

        $user = $this->demoUsers[$role] ?? $this->demoUsers['customer'];
        Auth::login($user);
        Session::flash('success', "Welcome back, {$user['name']}!");
        Response::redirect(Auth::homeUrl());
    }

    public function logout(): void
    {
        Auth::logout();
        Session::flash('info', 'You have been logged out.');
        Response::redirect('/login');
    }

    public function registerRole(): void
    {
        $this->view('public/register-role', ['title' => 'Choose Account Type']);
    }

    public function registerCustomer(): void
    {
        $this->view('public/register-customer', ['title' => 'Customer Registration']);
    }

    public function registerOwner(): void
    {
        $this->view('public/register-owner', ['title' => 'Venue Owner Registration']);
    }

    public function registerCoach(): void
    {
        $this->view('public/register-coach', ['title' => 'Coach Registration']);
    }
}
