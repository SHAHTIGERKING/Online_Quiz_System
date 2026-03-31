<?php
/**
 * Configuration Settings
 */

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'online_quiz_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// Site Settings
define('SITE_NAME', 'Online Quiz System');
define('BASE_URL', 'http://localhost/Online%20Quiz%20System%201');

// Path Settings
define('ROOT_PATH', dirname(__DIR__));
define('LOG_PATH', ROOT_PATH . '/storage/logs/system.log');

// Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start Session if not started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
