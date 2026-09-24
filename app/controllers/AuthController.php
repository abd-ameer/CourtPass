<?php
class AuthController extends Controller
{
    public function login(): void
    {
        if (Auth::check()) {
            $this->redirect(Auth::homeUrl());
        }
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

        // TODO: look up the user by email, password_verify(), reject deactivated accounts,
        // then Auth::login($user) and redirect to Auth::homeUrl().
        Session::flash('info', 'Login is not connected to the database yet.');
        $this->view('auth/login', ['title' => 'Log In', 'old' => $input, 'errors' => []]);
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
        $this->view('auth/register-role', ['title' => 'Create an Account']);
    }

    public function customerForm(): void
    {
        $this->view('auth/register-customer', ['title' => 'Customer Sign Up', 'old' => [], 'errors' => []]);
    }

    public function registerCustomer(): void
    {
        $this->verifyCsrf();
        $data = $this->request->all();
        $errors = self::accountErrors($data);
        $old = self::accountOld($data);

        if ($errors !== []) {
            http_response_code(422);
            $this->view('auth/register-customer', ['title' => 'Customer Sign Up', 'old' => $old, 'errors' => $errors]);
            return;
        }

        // TODO: create the users row (role customer) and its customer_profiles row in one
        // transaction, reject a duplicate email, log in and redirect to /customer/dashboard.
        Session::flash('info', 'Sign-up is not connected to the database yet.');
        $this->view('auth/register-customer', ['title' => 'Customer Sign Up', 'old' => $old, 'errors' => []]);
    }

    /** Field rules shared by every sign-up form (users table). */
    public static function accountErrors(array $data): array
    {
        $v = (new Validator($data))
            ->required('name', 'Full name')->maxLength('name', 100)
            ->required('email', 'Email')->email('email')->maxLength('email', 191)
            ->required('phone', 'Contact number')->maxLength('phone', 20)
            ->required('password', 'Password')->minLength('password', 8)->maxLength('password', 72);
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
