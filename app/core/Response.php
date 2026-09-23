<?php
class Response
{
    public static function json(array $data, int $status = 200): never
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit;
    }

    /** $path is app-relative, e.g. "/login". */
    public static function redirect(string $path): never
    {
        if (!headers_sent()) {
            header('Location: ' . url($path));
        }
        exit;
    }
}
