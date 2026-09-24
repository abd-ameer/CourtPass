<?php
/**
 * Who is logged in, and role checks.
 * Roles (one per account): customer, owner, coach, admin.
 * Login/logout controllers call Auth::login() / Auth::logout().
 */
class Auth
{
    public const ROLES = ['customer', 'owner', 'coach', 'admin'];

    /** Dashboard each role is sent to after login. */
    public const HOME = [
        'customer' => '/customer/dashboard',
        'owner'    => '/owner/dashboard',
        'coach'    => '/coach/dashboard',
        'admin'    => '/admin/dashboard',
    ];

    public const DEACTIVATED_MESSAGE = 'This account has been deactivated. Please contact CourtPass support.';

    /** $user needs at least id, role and name. */
    public static function login(array $user): void
    {
        Session::regenerate();
        Session::set('user', [
            'id'   => (int) $user['id'],
            'role' => $user['role'],
            'name' => $user['name'] ?? '',
        ]);
    }

    public static function logout(): void
    {
        Session::destroy();
    }

    public static function user(): ?array
    {
        return Session::get('user');
    }

    public static function id(): ?int
    {
        return self::user()['id'] ?? null;
    }

    public static function role(): ?string
    {
        return self::user()['role'] ?? null;
    }

    public static function check(): bool
    {
        return self::user() !== null;
    }

    public static function hasRole(string ...$roles): bool
    {
        return in_array(self::role(), $roles, true);
    }

    public static function homeUrl(): string
    {
        return self::HOME[self::role()] ?? '/';
    }

    /**
     * Guard used by the Router. Not logged in: 401 / redirect to login.
     * Account deactivated since login: signed out, then treated as not logged in.
     * Wrong role: 403 / redirect to own dashboard.
     */
    public static function guard(array $roles, Request $request): void
    {
        if (self::check() && !self::stillActive()) {
            self::logout();
            Session::start();
            Session::regenerate();
            if ($request->isApi()) {
                Response::json(['error' => self::DEACTIVATED_MESSAGE], 401);
            }
            Session::flash('error', self::DEACTIVATED_MESSAGE);
            Response::redirect('/login');
        }
        if (!self::check()) {
            if ($request->isApi()) {
                Response::json(['error' => 'Please log in.'], 401);
            }
            Session::flash('error', 'Please log in to continue.');
            Response::redirect('/login');
        }
        if ($roles !== [] && !self::hasRole(...$roles)) {
            if ($request->isApi()) {
                Response::json(['error' => 'You do not have access to this.'], 403);
            }
            Session::flash('error', 'You do not have access to that page.');
            Response::redirect(self::homeUrl());
        }
    }

    /** The session copy of the user can go stale, so guarded requests re-read the account. */
    private static function stillActive(): bool
    {
        $user = (new UserModel())->findById(self::id());
        return $user !== null && $user['status'] === 'active' && $user['role'] === self::role();
    }
}
