<?php
// public/index.php

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/database.php';
require_once __DIR__ . '/includes/functions.php';

// Check if the request is for the admin section
$requestUri = $_SERVER['REQUEST_URI'];
if (strpos($requestUri, '/admin') === 0) {
    // Let the admin routing handle this
    return false;
}

// Basic routing
$page = $_GET['page'] ?? 'home';
$validPages = ['home', 'about', 'contact', 'archive', 'single', 'work'];

if (!in_array($page, $validPages)) {
    header("HTTP/1.0 404 Not Found");
    $page = '404';
}

// Include partials
include __DIR__ . '/sections/header.php';
include __DIR__ . "/sections/{$page}.php";
include __DIR__ . '/sections/footer.php';