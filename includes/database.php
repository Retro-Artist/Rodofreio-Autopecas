<?php
// includes/database.php

require_once __DIR__ . '/../config/config.php';

try {
    // Attempt to connect to MySQL
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        ]
    );

    // Additional logging for debugging
    $debug_log = ERROR_LOG . '_debug.log';
    $debug_message = date('Y-m-d H:i:s') . " - Successful Database Connection\n";
    $debug_message .= "Host: " . DB_HOST . "\n";
    $debug_message .= "Database: " . DB_NAME . "\n";
    $debug_message .= "User: " . DB_USER . "\n\n";
    
    error_log($debug_message, 3, $debug_log);

} catch (PDOException $e) {
    // Detailed error logging
    $error_message = date('Y-m-d H:i:s') . " - Database Connection Error:\n";
    $error_message .= "Error Code: " . $e->getCode() . "\n";
    $error_message .= "Error Message: " . $e->getMessage() . "\n";
    $error_message .= "Host: " . DB_HOST . "\n";
    $error_message .= "Database: " . DB_NAME . "\n";
    $error_message .= "User: " . DB_USER . "\n\n";

    // Attempt to log the error
    error_log($error_message, 3, ERROR_LOG);

    // Show a generic error message to the user
    die("Erro de conexão com o banco de dados. Por favor, tente novamente mais tarde ou entre em contato com o suporte.");
}