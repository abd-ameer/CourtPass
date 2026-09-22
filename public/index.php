<?php
/**
 * CourtPass front controller.
 * Every request enters here, then goes Router -> Controller -> Service -> Model.
 */

// Let the PHP built-in server (php -S) serve static files directly
if (PHP_SAPI === 'cli-server') {
    $file = __DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if (is_file($file)) {
        return false;
    }
}

require dirname(__DIR__) . '/app/bootstrap.php';

$request = new Request();
$router  = new Router();

require CONFIG_PATH . '/routes.php';

$router->dispatch($request);
