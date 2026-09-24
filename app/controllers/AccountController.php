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

        // TODO: update name and phone of the logged-in user, then refresh the session name.
        Session::flash('info', 'Saving account details is not built yet.');
        $this->redirect('/account');
    }

    public function updatePassword(): void
    {
        $this->verifyCsrf();
        $data = $this->request->all();
        $v = (new Validator($data))
            ->required('current_password', 'Current password')
            ->required('new_password', 'New password')->minLength('new_password', 8)->maxLength('new_password', 72);
        $errors = $v->errors();
        if (!isset($errors['new_password']) && ($data['new_password'] ?? '') !== ($data['confirm_password'] ?? '')) {
            $errors['confirm_password'] = 'Passwords do not match.';
        }

        if ($errors !== []) {
            http_response_code(422);
            $this->view('account/edit', ['title' => 'Account Settings', 'account' => $this->account(), 'errors' => $errors], 'dashboard');
            return;
        }

        // TODO: password_verify the current password, then store a new password_hash.
        Session::flash('info', 'Changing passwords is not built yet.');
        $this->redirect('/account');
    }

    /** Session data until the account lookup exists. */
    private function account(): array
    {
        // TODO: load name, email and phone of Auth::id() from the users table.
        $user = Auth::user() ?? [];
        return ['name' => $user['name'] ?? '', 'email' => '', 'phone' => '', 'role' => $user['role'] ?? ''];
    }
}
