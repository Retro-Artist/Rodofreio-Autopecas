<?php
// admin/pages/logout.php

// Include configuration
require_once __DIR__ . '/../../config/config.php';

// Initialize session if not already started
init_session();

// Unset all session variables
$_SESSION = array();

// Destroy the session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Destroy the session
session_destroy();

// Redirect to login page
header("Location: login.php");
exit();