<?php
/**
 * CourtPass — Authentication & Session Management
 * Native PHP sessions with role-based access control.
 */

require_once __DIR__ . '/db.php';

/**
 * Start or resume a PHP session with secure settings.
 */
function initSession(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_set_cookie_params([
            'lifetime' => SESSION_LIFETIME,
            'path'     => '/',
            'httponly'  => true,
            'samesite'  => 'Lax',
        ]);
        session_start();
    }
}

/**
 * Attempt to log in a user with email and password.
 *
 * @param string $email
 * @param string $password
 * @return array ['success' => bool, 'message' => string, 'user' => array|null]
 */
function loginUser(string $email, string $password): array {
    $user = dbFetchOne(
        "SELECT id, name, email, phone, password_hash, role, status FROM users WHERE email = ?",
        's',
        [$email]
    );
    
    if (!$user) {
        return ['success' => false, 'message' => 'Invalid email or password.', 'user' => null];
    }
    
    if ($user['status'] === 'deactivated') {
        return ['success' => false, 'message' => 'Your account has been deactivated. Please contact support.', 'user' => null];
    }
    
    if (!password_verify($password, $user['password_hash'])) {
        return ['success' => false, 'message' => 'Invalid email or password.', 'user' => null];
    }
    
    // Set session data
    initSession();
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_role'] = $user['role'];
    $_SESSION['logged_in'] = true;
    $_SESSION['login_time'] = time();
    
    // Regenerate session ID to prevent fixation
    session_regenerate_id(true);
    
    // Trigger no-show detection on login (trigger-on-action)
    require_once __DIR__ . '/../api/reliability/no_show_check.php';
    checkAndMarkNoShows($user['id']);
    
    return ['success' => true, 'message' => 'Login successful.', 'user' => [
        'id'    => $user['id'],
        'name'  => $user['name'],
        'email' => $user['email'],
        'role'  => $user['role'],
    ]];
}

/**
 * Register a new user (customer or coach).
 *
 * @param string $name
 * @param string $email
 * @param string $phone
 * @param string $password
 * @param string $role 'customer' or 'coach'
 * @return array ['success' => bool, 'message' => string, 'user_id' => int|null]
 */
function registerUser(string $name, string $email, string $phone, string $password, string $role = 'customer'): array {
    // Validate role
    if (!in_array($role, ['customer', 'coach', 'owner'], true)) {
        return ['success' => false, 'message' => 'Invalid role specified.', 'user_id' => null];
    }
    
    // Check if email already exists
    $existing = dbFetchOne("SELECT id FROM users WHERE email = ?", 's', [$email]);
    if ($existing) {
        return ['success' => false, 'message' => 'An account with this email already exists.', 'user_id' => null];
    }
    
    // Hash password
    $hash = password_hash($password, PASSWORD_DEFAULT);
    
    try {
        dbBeginTransaction();
        
        dbQuery(
            "INSERT INTO users (name, email, phone, password_hash, role) VALUES (?, ?, ?, ?, ?)",
            'sssss',
            [$name, $email, $phone, $hash, $role]
        );
        
        $userId = dbLastInsertId();
        
        // Create reliability score entry for customers
        if ($role === 'customer') {
            dbQuery(
                "INSERT INTO reliability_scores (customer_id, score, tier) VALUES (?, 100.00, 'new_member')",
                'i',
                [$userId]
            );
        }
        
        // Create coach profile for coaches
        if ($role === 'coach') {
            dbQuery(
                "INSERT INTO coach_profiles (user_id) VALUES (?)",
                'i',
                [$userId]
            );
        }
        
        // Audit log
        require_once __DIR__ . '/audit.php';
        writeAuditLog($userId, 'user', $userId, 'user_registered', ['role' => $role]);
        
        dbCommit();
        
        return ['success' => true, 'message' => 'Registration successful.', 'user_id' => $userId];
        
    } catch (Exception $e) {
        dbRollback();
        error_log('Registration failed: ' . $e->getMessage());
        return ['success' => false, 'message' => 'Registration failed. Please try again.', 'user_id' => null];
    }
}

/**
 * Log out the current user.
 */
function logoutUser(): void {
    initSession();
    $_SESSION = [];
    
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params['path'], $params['domain'],
            $params['secure'], $params['httponly']
        );
    }
    
    session_destroy();
}

/**
 * Check if a user is currently logged in.
 * @return bool
 */
function isLoggedIn(): bool {
    initSession();
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

/**
 * Get the current logged-in user's ID.
 * @return int|null
 */
function currentUserId(): ?int {
    initSession();
    return $_SESSION['user_id'] ?? null;
}

/**
 * Get the current logged-in user's role.
 * @return string|null
 */
function currentUserRole(): ?string {
    initSession();
    return $_SESSION['user_role'] ?? null;
}

/**
 * Get the current logged-in user's name.
 * @return string|null
 */
function currentUserName(): ?string {
    initSession();
    return $_SESSION['user_name'] ?? null;
}

/**
 * Require the user to be logged in. Redirects to login if not.
 * For API endpoints, sends a 401 JSON response instead.
 *
 * @param bool $isApi Whether this is an API endpoint
 */
function requireLogin(bool $isApi = false): void {
    if (!isLoggedIn()) {
        if ($isApi) {
            http_response_code(401);
            echo json_encode(['error' => 'Authentication required.']);
            exit;
        }
        header('Location: ' . BASE_URL . '/login');
        exit;
    }
}

/**
 * Require the user to have a specific role.
 * Must be called after requireLogin().
 *
 * @param string|array $roles Allowed role(s)
 * @param bool $isApi
 */
function requireRole(string|array $roles, bool $isApi = false): void {
    $roles = (array) $roles;
    $currentRole = currentUserRole();
    
    if (!in_array($currentRole, $roles, true)) {
        if ($isApi) {
            http_response_code(403);
            echo json_encode(['error' => 'You do not have permission to access this resource.']);
            exit;
        }
        http_response_code(403);
        include __DIR__ . '/../pages/errors/403.php';
        exit;
    }
}

/**
 * Get full user data for the currently logged-in user.
 * @return array|null
 */
function getCurrentUser(): ?array {
    if (!isLoggedIn()) {
        return null;
    }
    
    return dbFetchOne(
        "SELECT id, name, email, phone, role, status, created_at FROM users WHERE id = ?",
        'i',
        [currentUserId()]
    );
}
