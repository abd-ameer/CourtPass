<?php
/**
 * Copy this file to config/config.php and fill in your own values.
 * config/config.php is git-ignored and must never be committed.
 */

// App
define('APP_NAME', 'CourtPass');
define('APP_DEBUG', true);              // false on the deployed server
define('APP_TIMEZONE', 'Asia/Colombo'); // all business logic uses this

// Database (XAMPP defaults)
define('DB_HOST', 'localhost');
define('DB_PORT', 3306);
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'courtpass');

// PayHere Sandbox
define('PAYHERE_MERCHANT_ID', 'YOUR_SANDBOX_MERCHANT_ID');
define('PAYHERE_MERCHANT_SECRET', 'YOUR_SANDBOX_MERCHANT_SECRET');
define('PAYHERE_CHECKOUT_URL', 'https://sandbox.payhere.lk/pay/checkout');
