<?php
// admin/pages/header.php
// Obtendo a página atual de forma segura
$current_page = isset($_GET['page']) ? $_GET['page'] : 'home';
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo - <?= SITE_NAME ?></title>
    <!-- CSS Base -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/styles.css">
    
    <!-- CSS Admin -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/styles.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin/header.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin/footer.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin/create.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin/home.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin/admin.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin/category.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin/product.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin/login.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin/forms.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Chart.js para os gráficos -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="admin">
    <header class="admin-header">
        <div class="container">
            <div class="header-brand">
                <h1><a href="<?= BASE_URL ?>/admin/index.php?page=home" style="text-decoration: none; color: inherit;"><?= SITE_NAME ?> Admin</a></h1>
            </div>
            <nav class="admin-nav">
                <ul>
                    <li><a href="<?= BASE_URL ?>/admin/index.php?page=home" <?= $current_page === 'home' ? 'class="active"' : '' ?>><i class="fas fa-th-large"></i> Dashboard</a></li>

                    <!-- Links de Produtos - Sem JavaScript ou dropdown -->
                    <li class="dropdown">
                        <a href="<?= BASE_URL ?>/admin/index.php?page=product_list" <?= in_array($current_page, ['product_list', 'product_create', 'product_update']) ? 'class="active"' : '' ?>><i class="fas fa-box"></i> Produtos</a>
                        <div class="dropdown-menu">
                            <a href="<?= BASE_URL ?>/admin/index.php?page=product_list"><i class="fas fa-list"></i> Listar Produtos</a>
                            <a href="<?= BASE_URL ?>/admin/index.php?page=product_create"><i class="fas fa-plus"></i> Novo Produto</a>
                        </div>
                    </li>

                    <!-- Links de Categorias - Sem JavaScript ou dropdown -->
                    <li class="dropdown">
                        <a href="<?= BASE_URL ?>/admin/index.php?page=category_list" <?= in_array($current_page, ['category_list', 'category_create', 'category_update', 'category_delete']) ? 'class="active"' : '' ?>><i class="fas fa-tags"></i> Categorias</a>
                        <div class="dropdown-menu">
                            <a href="<?= BASE_URL ?>/admin/index.php?page=category_list"><i class="fas fa-list"></i> Listar Categorias</a>
                            <a href="<?= BASE_URL ?>/admin/index.php?page=category_create"><i class="fas fa-plus"></i> Nova Categoria</a>
                        </div>
                    </li>
                    
                    <!-- Link para Configurações -->
                    <li>
                        <a href="<?= BASE_URL ?>/admin/index.php?page=settings" <?= $current_page === 'settings' ? 'class="active"' : '' ?>><i class="fas fa-cogs"></i> Configurações</a>
                    </li>

                    <li>
                        <form action="<?= BASE_URL ?>/admin/pages/logout.php" method="POST" style="display: inline;">
                            <button type="submit" class="logout-button"><i class="fas fa-sign-out-alt"></i> Sair</button>
                        </form>
                    </li>
                </ul>
            </nav>
        </div>
    </header>
    <script>
// Mobile Menu Script

    document.addEventListener('DOMContentLoaded', function() {
        // Mobile menu toggle
        const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
        const mainNavMenu = document.querySelector('.main-nav__menu');

        if (mobileMenuToggle && mainNavMenu) {
            mobileMenuToggle.addEventListener('click', function(e) {
                e.preventDefault();
                mainNavMenu.classList.toggle('active');
                this.classList.toggle('active');
            });
        }

        // Dropdown toggle for mobile
        const dropdowns = document.querySelectorAll('.main-nav__item--dropdown');

        dropdowns.forEach(function(dropdown) {
            const link = dropdown.querySelector('.dropdown-toggle');

            if (link) {
                link.addEventListener('click', function(e) {
                    // Only apply this behavior on mobile
                    if (window.innerWidth <= 768) {
                        e.preventDefault();
                        dropdown.classList.toggle('active');
                    }
                });
            }
        });

        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.main-nav') && !e.target.closest('.mobile-menu-toggle')) {
                if (mainNavMenu.classList.contains('active')) {
                    mainNavMenu.classList.remove('active');
                    mobileMenuToggle.classList.remove('active');
                }
            }
        });
    });
</script>
    <main class="container">