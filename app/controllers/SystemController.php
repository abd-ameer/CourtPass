<?php
/**
 * GET /api/health: quick check that routing, JSON output and the DB connection work.
 */
class SystemController extends Controller
{
    public function health(): void
    {
        try {
            Database::connection()->query('SELECT 1');
            $db = 'connected';
        } catch (Throwable $e) {
            $db = 'not connected' . (APP_DEBUG ? ': ' . $e->getMessage() : '');
        }

        $this->json([
            'app'      => APP_NAME,
            'status'   => 'ok',
            'time'     => now(),
            'timezone' => date_default_timezone_get(),
            'php'      => PHP_VERSION,
            'database' => $db,
        ]);
    }
}
