<?php
/**
 * Loads config, the autoloader, helpers, error handling and the session.
 * Included once by public/index.php (and by tests if needed).
 */

define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('VIEW_PATH', APP_PATH . '/views');

$configFile = CONFIG_PATH . '/config.php';
if (!is_file($configFile)) {
    http_response_code(500);
    exit('Missing config/config.php. Copy config/config.example.php to config/config.php and fill in your values.');
}
require $configFile;

date_default_timezone_set(APP_TIMEZONE);

/**
 * Autoloader (native PHP, no Composer).
 * Class name suffix decides the folder:
 *   ExampleController -> app/controllers/ExampleController.php
 *   ExampleService    -> app/services/ExampleService.php
 *   ExampleModel      -> app/models/ExampleModel.php
 * Anything else is looked up in app/core, then app/helpers.
 */
spl_autoload_register(function (string $class): void {
    $folders = ['Controller' => 'controllers', 'Service' => 'services', 'Model' => 'models'];

    foreach ($folders as $suffix => $folder) {
        if ($class !== $suffix && str_ends_with($class, $suffix)) {
            $file = APP_PATH . "/{$folder}/{$class}.php";
            if (is_file($file)) {
                require $file;
            }
            return;
        }
    }

    foreach (['core', 'helpers'] as $folder) {
        $file = APP_PATH . "/{$folder}/{$class}.php";
        if (is_file($file)) {
            require $file;
            return;
        }
    }
});

require APP_PATH . '/helpers/functions.php';

// Errors: show details only when APP_DEBUG is true
error_reporting(E_ALL);
ini_set('display_errors', APP_DEBUG ? '1' : '0');

set_exception_handler(function (Throwable $e): void {
    error_log('[CourtPass] ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
    $message = APP_DEBUG ? $e->getMessage() : 'Something went wrong. Please try again.';

    if (Request::current()?->isApi()) {
        Response::json(['error' => $message], 500);
    } else {
        http_response_code(500);
        View::render('errors/500', ['message' => $message]);
    }
});

Session::start();
