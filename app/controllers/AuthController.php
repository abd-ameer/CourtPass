<?php
class AuthController extends Controller
{
    public function login(): void
    {
        $this->redirectIfLoggedIn();
        $this->view('auth/login', ['title' => 'Log In', 'old' => [], 'errors' => []]);
    }

    public function authenticate(): void
    {
        $this->verifyCsrf();
        $input = ['email' => trim((string) $this->request->input('email', ''))];

        $v = (new Validator($this->request->all()))
            ->required('email', 'Email')->email('email')
            ->required('password', 'Password');

        if ($v->fails()) {
            http_response_code(422);
            $this->view('auth/login', ['title' => 'Log In', 'old' => $input, 'errors' => $v->errors()]);
            return;
        }

        try {
            $user = (new AuthService())->authenticate($input['email'], (string) $this->request->input('password', ''));
        } catch (ValidationException $e) {
            http_response_code(422);
            Session::flash('error', $e->getMessage());
            $this->view('auth/login', ['title' => 'Log In', 'old' => $input, 'errors' => []]);
            return;
        }

        Auth::login($user);
        $this->redirect(Auth::homeUrl());
    }

    public function logout(): void
    {
        $this->verifyCsrf();
        Auth::logout();
        Session::start();
        Session::regenerate();
        Session::flash('success', 'You have been logged out.');
        $this->redirect('/login');
    }

    public function chooseRole(): void
    {
        $this->redirectIfLoggedIn();
        $this->view('auth/register-role', ['title' => 'Create an Account']);
    }

    public function customerForm(): void
    {
        $this->redirectIfLoggedIn();
        $this->view('auth/register-customer', ['title' => 'Customer Sign Up', 'old' => [], 'errors' => []]);
    }

    public function registerCustomer(): void
    {
        $this->verifyCsrf();
        $this->redirectIfLoggedIn();
        $data = $this->request->all();
        $errors = self::accountErrors($data);
        $old = self::accountOld($data);

        if ($errors !== []) {
            http_response_code(422);
            $this->view('auth/register-customer', ['title' => 'Customer Sign Up', 'old' => $old, 'errors' => $errors]);
            return;
        }

        try {
            $user = (new AuthService())->registerCustomer($data);
        } catch (ValidationException $e) {
            http_response_code(422);
            $this->view('auth/register-customer', ['title' => 'Customer Sign Up', 'old' => $old, 'errors' => $e->errors()]);
            return;
        }

        Auth::login($user);
        Session::flash('success', 'Welcome to CourtPass, ' . $user['name'] . '! Your account is ready.');
        $this->redirect(Auth::homeUrl());
    }

    /** Field rules shared by every sign-up form (users table). */
    public static function accountErrors(array $data): array
    {
        $v = (new Validator($data))
            ->required('name', 'Full name')->maxLength('name', 100)
            ->required('email', 'Email')->email('email')->maxLength('email', 191)
            ->required('phone', 'Contact number')->maxLength('phone', 20)
            ->required('password', 'Password')->password('password');
        $errors = $v->errors();

        $phone = trim((string) ($data['phone'] ?? ''));
        if (!isset($errors['phone']) && !preg_match('/^\+?[0-9 ]{9,15}$/', $phone)) {
            $errors['phone'] = 'Enter a valid contact number.';
        }
        if (!isset($errors['password']) && ($data['password'] ?? '') !== ($data['confirm_password'] ?? '')) {
            $errors['confirm_password'] = 'Passwords do not match.';
        }
        return $errors;
    }

    /** Values to refill after a failed submit. Passwords are never refilled. */
    public static function accountOld(array $data): array
    {
        return [
            'name'  => trim((string) ($data['name'] ?? '')),
            'email' => trim((string) ($data['email'] ?? '')),
            'phone' => trim((string) ($data['phone'] ?? '')),
        ];
    }
}
