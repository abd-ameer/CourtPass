<?php
/**
 * Small global helpers for views and controllers.
 */

/** Escape output for HTML. Use on every value printed in a view. */
function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

/** Full URL path for an app route: url('/venues') -> /courtpass/venues */
function url(string $path = '/'): string
{
    return Request::basePath() . '/' . ltrim($path, '/');
}

/** URL for a file in public/assets: asset('css/app.css') */
function asset(string $path): string
{
    return Request::basePath() . '/assets/' . ltrim($path, '/');
}

/** Hidden CSRF input for HTML forms. */
function csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . e(Session::csrfToken()) . '">';
}

/** Current time in Asia/Colombo as 'Y-m-d H:i:s' (for SQL DATETIME). */
function now(): string
{
    return date('Y-m-d H:i:s');
}

/** "active" when the current path is $target or below it (sidebar and nav links). */
function is_active_route(string $target, ?string $current = null): string
{
    $current = $current ?? Request::current()?->path() ?? '';
    return ($target === $current || str_starts_with($current, $target . '/')) ? 'active' : '';
}

/** Money in LKR: lkr(1500) -> "LKR 1,500". */
function lkr(float|int|string $amount): string
{
    $amount = (float) $amount;
    return 'LKR ' . number_format($amount, fmod($amount, 1.0) === 0.0 ? 0 : 2);
}

/** SQL DATETIME or DATE for display: "Tue 29 Sep 2026, 08:00". */
function format_datetime(string $value, bool $withTime = true): string
{
    $ts = strtotime($value);
    if ($ts === false) {
        return $value;
    }
    return date($withTime ? 'D j M Y, H:i' : 'D j M Y', $ts);
}

/** Label for a locked status enum value from schema.sql. */
function status_label(string $status): string
{
    $labels = [
        'pending_payment'      => 'Awaiting Payment',
        'completed_unattended' => 'Completed (Not Attended)',
        'no_show'              => 'No-Show',
        'new_member'           => 'New Member',
        'cash_on_arrival'      => 'Cash on Arrival',
    ];
    return $labels[$status] ?? ucwords(str_replace('_', ' ', $status));
}

/** Badge for a status enum value; colours live in components.css (.badge-status-*). */
function status_badge(string $status): string
{
    $class = preg_replace('/[^a-z_]/', '', $status);
    return '<span class="badge badge-status-' . $class . '">' . e(status_label($status)) . '</span>';
}
