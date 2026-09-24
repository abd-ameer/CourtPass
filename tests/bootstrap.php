<?php
/**
 * PHPUnit bootstrap: loads the app the same way public/index.php does, then builds the test database.
 */

$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['REQUEST_URI'] = '/';

require dirname(__DIR__) . '/app/bootstrap.php';

// The app's handler renders an HTML error page; PHPUnit reports exceptions itself.
restore_exception_handler();

require __DIR__ . '/DatabaseTestCase.php';
DatabaseTestCase::createTestDatabase();
