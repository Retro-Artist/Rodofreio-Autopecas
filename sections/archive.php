<?php
// sections/archive.php

// Get current page number
$current_page = isset($_GET['page_num']) ? max(1, intval($_GET['page_num'])) : 1;
$per_page = 12; // Number of items per page
$offset = ($current_page - 1) * $per_page;

// Ordenação (modificado para remover opções de preço)
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'recent';

// Define ordem com base no parâmetro
switch ($sort) {
    case 'name':
        $order_by = "p.title ASC";
        break;
    case 'recent':
    default:
        $order_by = "p.created_at DESC";
        break;
}

// Initialize title and WHERE clause
$title = "Todos os Produtos";
$where_sql = "p.status = 'published'"; // Base condition - only show published items
$params = []; // Array para armazenar parâmetros da consulta
$category_filter = isset($_GET['category']) ? trim($_GET['category']) : '';
$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';

// Set up search query if a search term exists
if (!empty($search_term)) {
    $title = "Resultados para \"" . htmlspecialchars($search_term) . "\"";
    $where_sql .= " AND (p.title LIKE ? OR p.description LIKE ? OR p.content LIKE ?)";
    $search_param = '%' . $search_term . '%';
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
}
// Process category filter if present
elseif (!empty($category_filter)) {
    // Get category name for title
    try {
        $cat_query = "SELECT name FROM categories WHERE slug = ?";
        $cat_stmt = $pdo->prepare($cat_query);
        $cat_stmt->execute([$category_filter]);
        $category = $cat_stmt->fetch();

        if ($category) {
            $title = "Categoria: \"" . htmlspecialchars($category['name']) . "\"";
            $category_name = $category['name']; // Para uso no badge
        } else {
            $title = "Categoria não encontrada";
        }
    } catch (PDOException $e) {
        error_log("Erro ao buscar nome da categoria: " . $e->getMessage());
        $title = "Categoria: \"" . htmlspecialchars($category_filter) . "\"";
    }

    $where_sql .= " AND c.slug = ?";
    $params[] = $category_filter;
}

// Count total items for pagination
$count_query = "SELECT COUNT(*) FROM posts p 
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE " . $where_sql;

$count_stmt = $pdo->prepare($count_query);
$count_stmt->execute($params);
$total_items = $count_stmt->fetchColumn();
$total_pages = ceil($total_items / $per_page);

// Main query to get products for current page
$query = "SELECT p.id, p.title, p.slug, p.main_picture, p.description, 
                 p.featured, p.created_at, p.availability, p.tags,
                 c.name as category_name, c.slug as category_slug
          FROM posts p
          LEFT JOIN categories c ON p.category_id = c.id
          WHERE " . $where_sql . " 
          ORDER BY " . $order_by . " 
          LIMIT ?, ?";

$stmt = $pdo->prepare($query);

// Adicionar parâmetros já definidos
foreach ($params as $index => $param) {
    $stmt->bindValue($index + 1, $param);
}

// Adicionar parâmetros de paginação
$param_index = count($params) + 1;
$stmt->bindValue($param_index++, $offset, PDO::PARAM_INT);
$stmt->bindValue($param_index++, $per_page, PDO::PARAM_INT);

// Execute the query
$stmt->execute();
$products = $stmt->fetchAll();
?>

<main class="products-page">
    <!----------------------Hero Banner Section---------------------->
    <section class="hero-banner">
        <div class="hero-overlay"></div>
        <div class="whatsapp-floating">
            <a href="<?= getWhatsAppUrl(WHATSAPP_NUMBER) ?>" target="_blank">
                <i class="fab fa-whatsapp"></i>
            </a>
        </div>
    </section>

    <div class="products-page__wrapper">
        <div class="products-page__content">

            <!----------------------Filters Container---------------------->
            <section class="filters-section">
                <div class="filters-container">
                    <form action="<?= BASE_URL ?>/produtos" method="GET" class="filter-form">
                        <div class="filters-content">
                            <div class="filter-group">
                                <label for="category" class="filter-group__label">Categoria</label>
                                <select id="category" name="category" class="filter-group__select">
                                    <option value="">Todas as Categorias</option>
                                    <?php
                                    try {
                                        $nav_categories_query = "SELECT id, name, slug FROM categories ORDER BY name";
                                        $nav_categories_stmt = $pdo->prepare($nav_categories_query);
                                        $nav_categories_stmt->execute();
                                        $nav_categories = $nav_categories_stmt->fetchAll();

                                        foreach ($nav_categories as $cat) {
                                            $selected = ($category_filter === $cat['slug']) ? 'selected' : '';
                                            echo '<option value="' . htmlspecialchars($cat['slug']) . '" ' . $selected . '>' .
                                                htmlspecialchars($cat['name']) . '</option>';
                                        }
                                    } catch (PDOException $e) {
                                        error_log("Erro ao buscar categorias para filtro: " . $e->getMessage());
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="filter-group">
                                <label for="sort" class="filter-group__label">Ordenar por</label>
                                <select id="sort" name="sort" class="filter-group__select">
                                    <option value="recent" <?= $sort == 'recent' ? 'selected' : '' ?>>Mais recentes</option>
                                    <option value="name" <?= $sort == 'name' ? 'selected' : '' ?>>Nome (A-Z)</option>
                                </select>
                            </div>

                            <div class="filter-group">
                                <label for="search" class="filter-group__label">Buscar</label>
                                <input type="text" id="search" name="search" placeholder="Digite sua busca..."
                                    class="filter-group__input" value="<?= htmlspecialchars($search_term) ?>">
                            </div>

                            <div class="filter-actions">
                                <button type="submit" class="filter-button filter-button--search">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                                <a href="<?= BASE_URL ?>/produtos" class="filter-button filter-button--reset">
                                    Limpar Filtros
                                </a>
                            </div>
                        </div>

                        <?php if (!empty($search_term) || !empty($category_filter) || ($sort && $sort != 'recent')): ?>
                            <div class="active-filters">
                                <span class="active-filters__label">Filtros ativos:</span>

                                <?php if (!empty($search_term)): ?>
                                    <div class="filter-badge">
                                        <span class="filter-badge__label">Busca: </span>
                                        <span class="filter-badge__tag"><?= htmlspecialchars($search_term) ?></span>
                                        <a href="<?= BASE_URL ?>/produtos<?= !empty($category_filter) ? '/categoria/' . urlencode($category_filter) : '' ?><?= !empty($sort) && $sort != 'recent' ? '?sort=' . $sort : '' ?>" class="filter-badge__clear">×</a>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($category_filter) && isset($category['name'])): ?>
                                    <div class="filter-badge">
                                        <span class="filter-badge__label">Categoria: </span>
                                        <span class="filter-badge__tag"><?= htmlspecialchars($category['name']) ?></span>
                                        <a href="<?= BASE_URL ?>/produtos<?= !empty($search_term) ? '/busca/' . urlencode($search_term) : '' ?><?= !empty($sort) && $sort != 'recent' ? '?sort=' . $sort : '' ?>" class="filter-badge__clear">×</a>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($sort) && $sort != 'recent'): ?>
                                    <div class="filter-badge">
                                        <span class="filter-badge__label">Ordenação: </span>
                                        <span class="filter-badge__tag">
                                            <?php
                                            switch ($sort) {
                                                case 'name':
                                                    echo 'Nome (A-Z)';
                                                    break;
                                                default:
                                                    echo 'Mais recentes';
                                                    break;
                                            }
                                            ?>
                                        </span>
                                        <a href="<?= BASE_URL ?>/produtos<?= !empty($search_term) ? '/busca/' . urlencode($search_term) : '' ?><?= !empty($category_filter) ? '/categoria/' . urlencode($category_filter) : '' ?>" class="filter-badge__clear">×</a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </form>
                </div>
            </section>

            <!----------------------Products Content Section---------------------->
            <section class="products-content">
                <div class="products-header">
                    <h2 class="products-header__title"><?= htmlspecialchars($title) ?></h2>
                    <?php if ($total_items > 0): ?>
                        <p class="products-header__count">Exibindo <?= min($total_items, $per_page) ?> de <?= $total_items ?> produtos</p>
                    <?php endif; ?>
                </div>

                <?php if (empty($products)): ?>
                    <div class="no-results">
                        <p class="no-results__text">Nenhum produto encontrado. Por favor, tente uma busca diferente ou selecione outra categoria.</p>
                    </div>
                <?php else: ?>
                    <div class="products-grid">
                        <?php foreach ($products as $product):
                            $url = BASE_URL . '/produto/' . $product['slug'];
                        ?>
                            <div class="product-card"
                                data-product-id="<?= $product['id'] ?>"
                                data-product-name="<?= htmlspecialchars($product['title']) ?>"
                                data-product-image="<?= !empty($product['main_picture']) ? UPLOADS_URL . $product['main_picture'] : IMAGES_URL . 'placeholder.webp' ?>"
                                data-product-slug="<?= $product['slug'] ?>">

                                <a href="<?= $url ?>" class="product-card-link" aria-label="Ver detalhes de <?= htmlspecialchars($product['title']) ?>"></a>

                                <?php if (!empty($product['featured'])): ?>
                                    <div class="product-badge">Em Oferta</div>
                                <?php endif; ?>

                                <div class="product-image">
                                    <?php if (!empty($product['main_picture'])): ?>
                                        <img src="<?= UPLOADS_URL . $product['main_picture'] ?>" alt="<?= htmlspecialchars($product['title']) ?>">
                                    <?php else: ?>
                                        <img src="<?= IMAGES_URL ?>placeholder.webp" alt="<?= htmlspecialchars($product['title']) ?>">
                                    <?php endif; ?>
                                </div>

                                <div class="product-info">
                                    <h3 class="product-title"><?= htmlspecialchars($product['title']) ?></h3>
                                    <div class="product-contact">
                                        <i class="fab fa-whatsapp"></i>
                                        <span>Compra pelo WhatsApp</span>
                                    </div>
                                </div>


                                <!-- In sections/archive.php, modify the product action buttons -->
                                <div class="product-action-buttons">
                                    <a href="<?= $url ?>" class="product-btn-secondary" onclick="event.stopPropagation();">
                                        <i class="fas fa-eye"></i> Ver Detalhes
                                    </a>
                                    <a href="#" class="product-btn-primary" onclick="event.preventDefault(); event.stopPropagation();">
                                        <i class="fas fa-shopping-cart"></i> Adicionar ao Carrinho
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!----------------------Pagination Section---------------------->
                    <?php if ($total_pages > 1): ?>
                        <div class="pagination">
                            <div class="pagination-info">
                                Página <?= $current_page ?> de <?= $total_pages ?>
                            </div>
                            <div class="pagination-links">
                                <?php
                                // Construir URL base para paginação
                                $pagination_base_url = BASE_URL;

                                if (!empty($category_filter)) {
                                    $pagination_base_url .= '/produtos/categoria/' . urlencode($category_filter);
                                } elseif (!empty($search_term)) {
                                    $pagination_base_url .= '/produtos/busca/' . urlencode($search_term);
                                } else {
                                    $pagination_base_url .= '/produtos';
                                }

                                // Adicionar parâmetro de ordenação se necessário
                                $sort_param = (!empty($sort) && $sort != 'recent') ? 'sort=' . $sort . '&' : '';
                                $pagination_url = $pagination_base_url . '?' . $sort_param;
                                ?>

                                <?php if ($current_page > 1): ?>
                                    <a href="<?= $pagination_url ?>page_num=<?= $current_page - 1 ?>" class="pagination-link pagination-link--prev">« Anterior</a>
                                <?php endif; ?>

                                <?php
                                // Show pagination links
                                $range = 2; // Show 2 pages before and after current page
                                $start_page = max(1, $current_page - $range);
                                $end_page = min($total_pages, $current_page + $range);

                                // First page
                                if ($start_page > 1) {
                                    echo '<a href="' . $pagination_url . 'page_num=1" class="pagination-link">1</a>';

                                    if ($start_page > 2) {
                                        echo '<span class="pagination-ellipsis">...</span>';
                                    }
                                }

                                // Page numbers
                                for ($i = $start_page; $i <= $end_page; $i++) {
                                    $active_class = $i === $current_page ? ' pagination-link--active' : '';
                                    echo '<a href="' . $pagination_url . 'page_num=' . $i . '" class="pagination-link' . $active_class . '">' . $i . '</a>';
                                }

                                // Last page
                                if ($end_page < $total_pages) {
                                    if ($end_page < $total_pages - 1) {
                                        echo '<span class="pagination-ellipsis">...</span>';
                                    }
                                    echo '<a href="' . $pagination_url . 'page_num=' . $total_pages . '" class="pagination-link">' . $total_pages . '</a>';
                                }
                                ?>

                                <?php if ($current_page < $total_pages): ?>
                                    <a href="<?= $pagination_url ?>page_num=<?= $current_page + 1 ?>" class="pagination-link pagination-link--next">Próximo »</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </section>
        </div>
    </div>
</main>