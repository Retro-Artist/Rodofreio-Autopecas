<?php
// admin/index.php

ob_start();
session_start();

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';

// Check if user is already logged in
if (!isset($_SESSION['admin_logged_in'])) {
    // Not logged in, redirect to login page
    header("Location: " . BASE_URL . "/admin/pages/login.php");
    exit();
}

// Get the requested page
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// List of valid admin pages - Adding category_delete and settings
$validPages = [
    'home', 
    'product_list', 
    'product_create', 
    'product_update', 
    'product_delete', 
    'category_list', 
    'category_create', 
    'category_update',
    'category_delete',
    'settings'
];

// Validate the page parameter
if (!in_array($page, $validPages)) {
    $page = '404';
}

// Include the header
include __DIR__ . '/pages/admin_header.php';

// Verificar se o arquivo existe antes de incluí-lo
$page_file = __DIR__ . '/pages/' . $page . '.php';
if (file_exists($page_file)) {
    include $page_file;
} else {
    // Se o arquivo não existir, redirecionar para a página 404
    include __DIR__ . '/pages/404.php';
}

// Include the footer
include __DIR__ . '/pages/admin_footer.php';