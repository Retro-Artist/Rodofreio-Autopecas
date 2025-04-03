<?php
// config/config.php - Configuration file for Rodofreios Autopeças

// ===================================
// Site Configuration
// ===================================

define('MODE', 'Development'); // Development or Production
define('SITE_NAME', 'Rodofreios Autopeças');
define('WHATSAPP_NUMBER', '557736396867');

// ===================================
// Database Configuration
// ===================================
if (MODE === 'Development') {
    define('BASE_URL', 'http://localhost:8888');
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'rodofreios');
    define('DB_USER', 'root');
    define('DB_PASS', 'root');
} else {
    define('BASE_URL', 'https://rodofreios.com.br');
    define('DB_HOST', 'db.whm07.bs2.com.br');
    define('DB_NAME', 'rodofreios');
    define('DB_USER', 'rodofreios');
    define('DB_PASS', 'tuu9tha4ahthU@');
}

// ===================================
// Path configuration
// ===================================

define('UPLOADS_URL', BASE_URL . '/uploads/');
define('IMAGES_URL', BASE_URL . '/assets/img/');


// ===================================
// Error Handling & Logging
// ===================================
// Create log directory if it doesn't exist
$log_directory = __DIR__ . '/../logs';
if (!is_dir($log_directory)) {
    mkdir($log_directory, 0755, true);
}
define('ERROR_LOG', $log_directory . '/error.log');

// Error Reporting (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ===================================
// Time & Locale Settings
// ===================================
date_default_timezone_set('America/Sao_Paulo');

// ===================================
// Session Management
// ===================================
/**
 * Initialize session if not already started
 */
function init_session()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

// Call session initialization
init_session();
