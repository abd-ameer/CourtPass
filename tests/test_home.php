<?php
require_once __DIR__ . '/../app/bootstrap.php';

$router = new Router();
require __DIR__ . '/../config/routes.php';

$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/';
$_SERVER['SCRIPT_NAME'] = '/public/index.php';

$request = new Request();

ob_start();
$router->dispatch($request);
$output = ob_get_clean();

echo "Status: SUCCESS\n";
echo "Output length: " . strlen($output) . " bytes\n";
echo "Contains Title: " . (str_contains($output, 'Book Sports Venues, Courts &amp; Coaches in Sri Lanka') ? 'YES' : 'NO') . "\n";
echo "Contains Featured Venues: " . (str_contains($output, 'Colombo Futsal Club') ? 'YES' : 'NO') . "\n";
echo "Contains Coaching: " . (str_contains($output, 'Coach Dilshan Perera') ? 'YES' : 'NO') . "\n";
echo "Contains Brand Logo: " . (str_contains($output, 'courtpasslogo.png') ? 'YES' : 'NO') . "\n";
