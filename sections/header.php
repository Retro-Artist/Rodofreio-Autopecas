<?php
// sections/header.php
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= SITE_NAME ?> - Peças Automotivas para veículos nacionais e importados</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/styles.css">
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/img/favicon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <header class="site-header">
        <!-- Top Bar -->
        <div class="top-bar">
            <div class="container">
                <div class="top-bar__content">
                    <div class="top-bar__item">
                        <a href="<?= BASE_URL ?>/contato" class="top-bar__link">PRECISA DE AJUDA?</a>
                    </div>
                    <div class="top-bar__item">
                        <a href="<?= BASE_URL ?>/sobre" class="top-bar__link">COMPRE CONOSCO E RECEBA SUA PEÇA EM CASA</a>
                    </div>
                    <div class="top-bar__item">
                        <a href="https://wa.me/<?= WHATSAPP_NUMBER ?>" class="top-bar__link" target="_blank">WHATSAPP: <?=WHATSAPP_NUMBER?></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Header Content -->
        <div class="site-header__main">
            <div class="container">
                <div class="site-header__wrapper">
                    <!-- Logo -->
                    <div class="site-header__logo">
                        <a href="<?= BASE_URL ?>">
                            <img src="<?= IMAGES_URL ?>logo.webp" alt="<?= SITE_NAME ?>" class="site-logo">
                        </a>
                    </div>

                    <!-- Search Bar (hidden on mobile) -->
                    <div class="site-header__search">
                        <form action="<?= BASE_URL ?>/produtos" method="GET" class="search-form">
                            <input type="text" name="search" placeholder="O que você procura?" class="search-form__input" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                            <button type="submit" class="search-form__button" aria-label="Buscar">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>

                    <!-- Header Controls -->
                    <div class="site-header__controls">
                        <!-- Cart Icon -->
                        <a href="#" class="header-icon cart-icon" id="cart-toggle">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="cart-count" style="display: none;">0</span>
                        </a>

                        <!-- Mobile Menu Toggle (hidden on desktop) -->
                        <button class="header-icon mobile-menu-toggle" aria-label="Menu">
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navegação Menu com Barra Vermelha -->
        <nav class="main-nav">
            <div class="container">
                <!-- Mobile Search (always visible on mobile) -->
                <div class="mobile-search-wrapper">
                    <form action="<?= BASE_URL ?>/produtos" method="GET" class="mobile-search-form">
                        <input type="text" name="search" placeholder="O que você procura?" class="mobile-search-form__input" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                        <button type="submit" class="mobile-search-form__button" aria-label="Buscar">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
                
                <ul class="main-nav__menu">
                    <!-- Regular menu items (hidden on mobile until menu button is clicked) -->
                    <li class="main-nav__item">
                        <a href="<?= BASE_URL ?>/" class="main-nav__link">Home</a>
                    </li>
                    <li class="main-nav__item">
                        <a href="<?= BASE_URL ?>/produtos" class="main-nav__link">Todos os Produtos</a>
                    </li>
                    <li class="main-nav__item main-nav__item--dropdown">
                        <a href="<?= BASE_URL ?>/produtos" class="main-nav__link dropdown-toggle">
                            Categorias <i class="fas fa-chevron-down"></i>
                        </a>
                        <div class="dropdown-menu">
                            <?php
                            // Buscar categorias do banco de dados
                            try {
                                $categories_query = "SELECT id, name, slug FROM categories ORDER BY name";
                                $categories_stmt = $pdo->prepare($categories_query);
                                $categories_stmt->execute();
                                $categories = $categories_stmt->fetchAll();

                                // Exibir cada categoria no menu
                                foreach ($categories as $category) {
                                    echo '<a href="' . BASE_URL . '/produtos/categoria/' . urlencode($category['slug']) . '" class="dropdown-menu__item">' . htmlspecialchars($category['name']) . '</a>';
                                }

                                // Se não houver categorias
                                if (empty($categories)) {
                                    echo '<a href="' . BASE_URL . '/produtos" class="dropdown-menu__item">Todos os produtos</a>';
                                }
                            } catch (PDOException $e) {
                                // Em caso de erro, mostrar pelo menos um link padrão
                                error_log("Erro ao buscar categorias: " . $e->getMessage());
                                echo '<a href="' . BASE_URL . '/produtos" class="dropdown-menu__item">Todos os produtos</a>';
                            }
                            ?>
                        </div>
                    </li>
                    <li class="main-nav__item">
                        <a href="<?= BASE_URL ?>/sobre" class="main-nav__link">Sobre Nós</a>
                    </li>
                    <li class="main-nav__item">
                        <a href="<?= BASE_URL ?>/trabalhe-conosco" class="main-nav__link">Trabalhe Conosco</a>
                    </li>
                    <li class="main-nav__item">
                        <a href="<?= BASE_URL ?>/contato" class="main-nav__link">Contato</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Cart Sidebar -->
    <div class="cart-sidebar" id="cart-sidebar">
        <div class="cart-sidebar__header">
            <h3>Seu Carrinho</h3>
            <button class="cart-close" id="cart-close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="cart-sidebar__content">
            <div class="cart-items" id="cart-items">
                <!-- Cart items will be dynamically added here -->
            </div>
            <div class="cart-empty" id="cart-empty">
                <i class="fas fa-shopping-cart"></i>
                <p>Seu carrinho está vazio</p>
            </div>
        </div>
        <div class="cart-sidebar__footer">
            <div class="cart-total">
                <span>Total:</span>
                <span class="cart-total-value" id="cart-total-value">0 itens</span>
            </div>
            <div class="cart-actions">
                <a href="#" class="cart-button cart-button--checkout" id="cart-checkout">
                    <i class="fas fa-check"></i> Finalizar Compra
                </a>
                <button class="cart-button cart-button--clear" id="cart-clear">
                    <i class="fas fa-trash-alt"></i> Limpar Carrinho
                </button>
            </div>
        </div>
    </div>
    <div class="cart-overlay" id="cart-overlay"></div>

    <!-- Mobile Menu Script - Versão simplificada -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM carregado - Script de menu inicializado');

            // Referências aos elementos
            const menuButton = document.querySelector('.mobile-menu-toggle');
            const mobileMenu = document.querySelector('.main-nav__menu');

            // Verificar se os elementos existem
            if (!menuButton || !mobileMenu) {
                console.error('Elementos do menu não encontrados!');
                return;
            }

            console.log('Elementos do menu encontrados, adicionando evento de clique');

            // Adicionar evento de clique ao botão do menu
            menuButton.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                console.log('Botão de menu clicado');

                // Toggle da classe 'active' no menu
                if (mobileMenu.classList.contains('active')) {
                    mobileMenu.classList.remove('active');
                    menuButton.classList.remove('active');
                    console.log('Menu fechado');
                } else {
                    mobileMenu.classList.add('active');
                    menuButton.classList.add('active');
                    console.log('Menu aberto');
                }
            });

            // Evento de clique para os dropdowns no mobile
            const dropdownLinks = document.querySelectorAll('.main-nav__item--dropdown > .main-nav__link');

            dropdownLinks.forEach(function(link) {
                link.addEventListener('click', function(e) {
                    if (window.innerWidth <= 768) {
                        e.preventDefault();
                        e.stopPropagation();

                        const parentLi = this.parentNode;
                        parentLi.classList.toggle('active');
                        console.log('Dropdown toggled');
                    }
                });
            });
        });
    </script>