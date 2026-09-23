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
