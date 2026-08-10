<?php
/**
 * CourtPass — Helper Functions
 * Sanitization, CSRF tokens, date/time helpers, response utilities.
 */

/**
 * Sanitize a string for safe output in HTML.
 * @param string|null $str
 * @return string
 */
function sanitize(?string $str): string {
    if ($str === null) return '';
    return htmlspecialchars(trim($str), ENT_QUOTES, 'UTF-8');
}

/**
 * Sanitize and validate an email address.
 * @param string $email
 * @return string|false
 */
function sanitizeEmail(string $email): string|false {
    $email = trim($email);
    return filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : false;
}

/**
 * Sanitize a phone number (Sri Lankan format).
 * @param string $phone
 * @return string
 */
function sanitizePhone(string $phone): string {
    return preg_replace('/[^0-9+]/', '', trim($phone));
}

// ─── CSRF Protection ────────────────────────────────────────

/**
 * Generate a CSRF token and store it in the session.
 * @return string
 */
function generateCsrfToken(): string {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    
    return $_SESSION['csrf_token'];
}

/**
 * Validate a CSRF token from a form submission.
 * @param string $token
 * @return bool
 */
function validateCsrfToken(string $token): bool {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Output a hidden CSRF token input field.
 * @return string
 */
function csrfField(): string {
    return '<input type="hidden" name="csrf_token" value="' . sanitize(generateCsrfToken()) . '">';
}

/**
 * Validate CSRF token from POST request. Sends 403 on failure.
 * @param bool $isApi
 */
function requireCsrf(bool $isApi = false): void {
    $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    
    if (!validateCsrfToken($token)) {
        if ($isApi) {
            http_response_code(403);
            echo json_encode(['error' => 'Invalid security token. Please refresh and try again.']);
            exit;
        }
        http_response_code(403);
        die('Invalid security token.');
    }
}

// ─── Date/Time Helpers ──────────────────────────────────────

/**
 * Get the current datetime in Asia/Colombo timezone.
 * @param string $format
 * @return string
 */
function now(string $format = 'Y-m-d H:i:s'): string {
    return (new DateTime('now', new DateTimeZone('Asia/Colombo')))->format($format);
}

/**
 * Get today's date in Asia/Colombo.
 * @return string Y-m-d
 */
function today(): string {
    return now('Y-m-d');
}

/**
 * Get the day of week (0=Sunday ... 6=Saturday) for a given date.
 * @param string $date Y-m-d
 * @return int
 */
function dayOfWeek(string $date): int {
    return (int)(new DateTime($date, new DateTimeZone('Asia/Colombo')))->format('w');
}

/**
 * Calculate hours between now and a future datetime.
 * @param string $date Y-m-d
 * @param string $time H:i:s
 * @return float Hours until the slot (negative if past)
 */
function hoursUntil(string $date, string $time): float {
    $slotTime = new DateTime("$date $time", new DateTimeZone('Asia/Colombo'));
    $nowTime = new DateTime('now', new DateTimeZone('Asia/Colombo'));
    $diff = $nowTime->diff($slotTime);
    $hours = ($diff->days * 24) + $diff->h + ($diff->i / 60);
    return $diff->invert ? -$hours : $hours;
}

/**
 * Format a date for human display.
 * @param string $date
 * @param string $format
 * @return string
 */
function formatDate(string $date, string $format = 'M j, Y'): string {
    return (new DateTime($date, new DateTimeZone('Asia/Colombo')))->format($format);
}

/**
 * Format a time for human display (12-hour format).
 * @param string $time
 * @return string
 */
function formatTime(string $time): string {
    return (new DateTime($time))->format('g:i A');
}

/**
 * Check if a slot datetime is in the past.
 * @param string $date Y-m-d
 * @param string $time H:i:s
 * @return bool
 */
function isSlotPast(string $date, string $time): bool {
    return hoursUntil($date, $time) < 0;
}

// ─── Response Helpers ───────────────────────────────────────

/**
 * Send a JSON response and exit.
 * @param array $data
 * @param int $statusCode
 */
function jsonResponse(array $data, int $statusCode = 200): void {
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * Send a JSON error response and exit.
 * @param string $message
 * @param int $statusCode
 */
function jsonError(string $message, int $statusCode = 400): void {
    jsonResponse(['error' => $message], $statusCode);
}

/**
 * Send a JSON success response and exit.
 * @param string $message
 * @param array $extra Additional data to include
 */
function jsonSuccess(string $message, array $extra = []): void {
    jsonResponse(array_merge(['success' => true, 'message' => $message], $extra));
}

// ─── Validation Helpers ─────────────────────────────────────

/**
 * Validate required POST fields.
 * @param array $fields List of required field names
 * @return array|null Returns null if valid, or error response data if invalid
 */
function validateRequired(array $fields): ?string {
    foreach ($fields as $field) {
        if (!isset($_POST[$field]) || trim($_POST[$field]) === '') {
            return "The field '{$field}' is required.";
        }
    }
    return null;
}

/**
 * Get a sanitized POST value.
 * @param string $key
 * @param mixed $default
 * @return string
 */
function postVal(string $key, mixed $default = ''): string {
    return isset($_POST[$key]) ? sanitize($_POST[$key]) : $default;
}

/**
 * Get a sanitized GET value.
 * @param string $key
 * @param mixed $default
 * @return string
 */
function getVal(string $key, mixed $default = ''): string {
    return isset($_GET[$key]) ? sanitize($_GET[$key]) : $default;
}

// ─── Misc Helpers ───────────────────────────────────────────

/**
 * Generate a random unguessable token.
 * @param int $length Byte length (output will be double in hex)
 * @return string
 */
function generateToken(int $length = 32): string {
    return bin2hex(random_bytes($length));
}

/**
 * Format a currency amount in LKR.
 * @param float $amount
 * @return string
 */
function formatCurrency(float $amount): string {
    return 'Rs. ' . number_format($amount, 2);
}

/**
 * Generate a PayHere order ID.
 * @param string $prefix
 * @param int $id
 * @return string
 */
function generateOrderId(string $prefix, int $id): string {
    return $prefix . '-' . str_pad($id, 6, '0', STR_PAD_LEFT) . '-' . time();
}

/**
 * Get sport type display name.
 * @param string $type
 * @return string
 */
function sportName(string $type): string {
    return match ($type) {
        'futsal'       => 'Futsal',
        'badminton'    => 'Badminton',
        'pickleball'   => 'Pickleball',
        'squash'       => 'Squash',
        'billiards'    => 'Billiards',
        'carrom'       => 'Carrom',
        'table_tennis' => 'Table Tennis',
        default        => ucfirst($type),
    };
}

/**
 * Get sport type icon (emoji).
 * @param string $type
 * @return string
 */
function sportIcon(string $type): string {
    return match ($type) {
        'futsal'       => '⚽',
        'badminton'    => '🏸',
        'pickleball'   => '🏓',
        'squash'       => '🎾',
        'billiards'    => '🎱',
        'carrom'       => '🎯',
        'table_tennis' => '🏓',
        default        => '🏟️',
    };
}

/**
 * Get booking status display badge class.
 * @param string $status
 * @return string CSS class name
 */
function statusBadgeClass(string $status): string {
    return match ($status) {
        'pending'    => 'badge--warning',
        'confirmed'  => 'badge--info',
        'completed'  => 'badge--success',
        'cancelled'  => 'badge--danger',
        'no_show'    => 'badge--danger',
        'approved'   => 'badge--success',
        'rejected'   => 'badge--danger',
        'deactivated'=> 'badge--muted',
        'active'     => 'badge--success',
        'sold'       => 'badge--success',
        'expired'    => 'badge--muted',
        'revoked'    => 'badge--muted',
        default      => 'badge--default',
    };
}

/**
 * Get display-friendly status label.
 * @param string $status
 * @return string
 */
function statusLabel(string $status): string {
    return ucfirst(str_replace('_', ' ', $status));
}
