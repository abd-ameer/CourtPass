<?php
class AccountController extends Controller
{
    public function edit(): void
    {
        $this->view('account/edit', [
            'title'   => 'Account Settings',
            'account' => $this->account(),
            'errors'  => [],
        ], 'dashboard');
    }

    public function update(): void
    {
        $this->verifyCsrf();
        $data = $this->request->all();
        $v = (new Validator($data))
            ->required('name', 'Full name')->maxLength('name', 100)
            ->required('phone', 'Contact number')->maxLength('phone', 20);
        $errors = $v->errors();
        $phone = trim((string) ($data['phone'] ?? ''));
        if (!isset($errors['phone']) && !preg_match('/^\+?[0-9 ]{9,15}$/', $phone)) {
            $errors['phone'] = 'Enter a valid contact number.';
        }

        $account = array_merge($this->account(), ['name' => trim((string) ($data['name'] ?? '')), 'phone' => $phone]);
        if ($errors !== []) {
            http_response_code(422);
            $this->view('account/edit', ['title' => 'Account Settings', 'account' => $account, 'errors' => $errors], 'dashboard');
            return;
        }

        $user = (new AccountService())->updateDetails(Auth::id(), $account['name'], $phone);
        Auth::login($user);
        Session::flash('success', 'Your account details have been saved.');
        $this->redirect('/account');
    }

    public function updatePassword(): void
    {
        $this->verifyCsrf();
        $data = $this->request->all();
        $v = (new Validator($data))
            ->required('current_password', 'Current password')
            ->required('new_password', 'New password')->password('new_password');
        $errors = $v->errors();
        if (!isset($errors['new_password']) && ($data['new_password'] ?? '') !== ($data['confirm_password'] ?? '')) {
            $errors['confirm_password'] = 'Passwords do not match.';
        }

        if ($errors !== []) {
            http_response_code(422);
            $this->view('account/edit', ['title' => 'Account Settings', 'account' => $this->account(), 'errors' => $errors], 'dashboard');
            return;
        }

        try {
            (new AccountService())->changePassword(
                Auth::id(),
                (string) ($data['current_password'] ?? ''),
                (string) $data['new_password']
            );
        } catch (ValidationException $e) {
            http_response_code(422);
            $this->view('account/edit', ['title' => 'Account Settings', 'account' => $this->account(), 'errors' => $e->errors()], 'dashboard');
            return;
        }

        Session::regenerate();
        Session::flash('success', 'Your password has been changed.');
        $this->redirect('/account');
    }

    private function account(): array
    {
        return (new AccountService())->details(Auth::id());
    }
}
