<?php
/**
 * Wraps the incoming HTTP request.
 * Works under XAMPP (http://localhost/courtpass/...) and php -S.
 */
class Request
{
    private static ?Request $current = null;
    private array $body;

    public function __construct()
    {
        self::$current = $this;

        $type = $_SERVER['CONTENT_TYPE'] ?? '';
        if (str_contains($type, 'application/json')) {
            $this->body = json_decode(file_get_contents('php://input'), true) ?? [];
        } else {
            $this->body = $_POST;
        }
    }

    public static function current(): ?Request
    {
        return self::$current;
    }

    /** URL prefix of the app, e.g. "/courtpass" under XAMPP or "" with php -S. */
    public static function basePath(): string
    {
        $dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
        $dir = preg_replace('#/public$#', '', $dir);
        return rtrim($dir, '/');
    }

    public function method(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    /** Path without the base prefix, always starting with "/". */
    public function path(): string
    {
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        $base = self::basePath();
        if ($base !== '' && str_starts_with($path, $base)) {
            $path = substr($path, strlen($base));
        }
        $path = '/' . trim($path, '/');
        return $path === '/public' ? '/' : $path;
    }

    public function isApi(): bool
    {
        return str_starts_with($this->path(), '/api/') || $this->path() === '/api';
    }

    /** Value from the POST / JSON body. */
    public function input(string $key, mixed $default = null): mixed
    {
        return $this->body[$key] ?? $default;
    }

    public function all(): array
    {
        return $this->body;
    }

    /** Value from the query string. */
    public function query(string $key, mixed $default = null): mixed
    {
        return $_GET[$key] ?? $default;
    }

    public function header(string $name): ?string
    {
        $key = 'HTTP_' . strtoupper(str_replace('-', '_', $name));
        return $_SERVER[$key] ?? null;
    }
}
