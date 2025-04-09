<?php
// admin/pages/product_list.php


// Verificar se existe mensagem na sessão (da operação de exclusão)
$message = '';
$messageClass = '';

if (isset($_SESSION['product_message'])) {
    $message = $_SESSION['product_message'];
    $messageClass = $_SESSION['product_message_class'] ?? 'info';

    // Limpar a mensagem da sessão
    unset($_SESSION['product_message']);
    unset($_SESSION['product_message_class']);
}

// Parâmetros de busca e filtros
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
$status = isset($_GET['status']) ? $_GET['status'] : '';
$availability = isset($_GET['availability']) ? (int)$_GET['availability'] : -1; // -1 = todos
$current_page = isset($_GET['page_num']) ? max(1, (int)$_GET['page_num']) : 1;
$items_per_page = 15;


// Consulta base para contagem e para listagem
$base_query = "FROM posts p
               LEFT JOIN categories c ON p.category_id = c.id
               WHERE 1=1";

$params = [];

// Adicionar condições com base nos filtros
if (!empty($search)) {
    $base_query .= " AND (p.title LIKE ? OR p.description LIKE ? OR p.original_code LIKE ? OR p.manufacturer_code LIKE ?)";
    $search_param = "%{$search}%";
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
}

if ($category_id > 0) {
    $base_query .= " AND p.category_id = ?";
    $params[] = $category_id;
}

if ($status === 'published' || $status === 'draft') {
    $base_query .= " AND p.status = ?";
    $params[] = $status;
}

if ($availability >= 0) {
    $base_query .= " AND p.availability = ?";
    $params[] = $availability;
}

// Contar total de produtos com os filtros aplicados
$count_query = "SELECT COUNT(*) " . $base_query;
$count_stmt = $pdo->prepare($count_query);
$count_stmt->execute($params);
$total_items = $count_stmt->fetchColumn();

// Calcular paginação
$total_pages = ceil($total_items / $items_per_page);
$offset = ($current_page - 1) * $items_per_page;

// Consulta para obter produtos da página atual
$product_query = "SELECT p.id, p.title, p.original_code, p.manufacturer_code, 
                  p.status, p.featured, p.availability, p.main_picture, 
                  p.created_at, c.name as category_name, p.slug 
                  " . $base_query . " 
                  ORDER BY p.created_at DESC 
                  LIMIT ?, ?";

$product_stmt = $pdo->prepare($product_query);
$param_position = 1;

foreach ($params as $param) {
    $product_stmt->bindValue($param_position++, $param);
}

$product_stmt->bindValue($param_position++, $offset, PDO::PARAM_INT);
$product_stmt->bindValue($param_position++, $items_per_page, PDO::PARAM_INT);
$product_stmt->execute();
$products = $product_stmt->fetchAll();

// Buscar todas as categorias para o filtro
try {
    $categories_query = "SELECT id, name FROM categories ORDER BY name";
    $categories_stmt = $pdo->prepare($categories_query);
    $categories_stmt->execute();
    $categories = $categories_stmt->fetchAll();
} catch (PDOException $e) {
    error_log($e->getMessage());
    $categories = [];
}
?>

<div class="product-list-page">
    <!-- Adicionar este código em product_list.php logo após a abertura da div "product-list-page" -->

    <?php if ($message): ?>
        <div class="message <?= $messageClass ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <div class="page-header">
        <h2><i class="fas fa-box"></i> Gerenciar Produtos</h2>
        <div class="page-actions">
            <a href="index.php?page=product_create" class="action-button">
                <i class="fas fa-plus"></i> Novo Produto
            </a>
        </div>
    </div>

    <!-- Filtros de Pesquisa -->
    <div class="filters-card">
        <form method="GET" action="index.php" class="filters-form">
            <input type="hidden" name="page" value="product_list">

            <div class="filters-row">
                <div class="filter-group">
                    <label for="search">Buscar</label>
                    <input type="text" id="search" name="search" value="<?= htmlspecialchars($search) ?>"
                        placeholder="Nome, descrição ou códigos">
                </div>

                <div class="filter-group">
                    <label for="category_id">Categoria</label>
                    <select id="category_id" name="category_id">
                        <option value="0">Todas as categorias</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>" <?= ($category_id == $category['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="">Todos</option>
                        <option value="published" <?= ($status === 'published') ? 'selected' : '' ?>>Publicado</option>
                        <option value="draft" <?= ($status === 'draft') ? 'selected' : '' ?>>Rascunho</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="availability">Disponibilidade</label>
                    <select id="availability" name="availability">
                        <option value="-1">Todos</option>
                        <option value="1" <?= ($availability === 1) ? 'selected' : '' ?>>Disponível</option>
                        <option value="0" <?= ($availability === 0) ? 'selected' : '' ?>>Indisponível</option>
                    </select>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="filter-button">
                        <i class="fas fa-search"></i> Filtrar
                    </button>
                    <a href="index.php?page=product_list" class="filter-reset">
                        <i class="fas fa-times"></i> Limpar
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Lista de Produtos -->
    <div class="data-container">
        <?php if (empty($products)): ?>
            <div class="empty-state">
                <i class="fas fa-box-open"></i>
                <p>Nenhum produto encontrado com os filtros atuais</p>
                <a href="index.php?page=product_create" class="action-button">Adicionar Primeiro Produto</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="data-table products-table">
                    <thead>
                        <tr>
                            <th width="60">Imagem</th>
                            <th>Produto</th>
                            <th>Código Original</th>
                            <th>Código Fabricante</th>
                            <th>Categoria</th>
                            <th>WhatsApp</th>
                            <th>Status</th>
                            <th width="120">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td class="product-image-cell">
                                    <?php if (!empty($product['main_picture'])): ?>
                                        <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($product['main_picture']) ?>"
                                            alt="<?= htmlspecialchars($product['title']) ?>" class="product-thumbnail">
                                    <?php else: ?>
                                        <div class="no-thumbnail"><i class="fas fa-image"></i></div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="product-title"><?= htmlspecialchars($product['title']) ?></div>
                                    <?php if ($product['featured']): ?>
                                        <span class="status-badge status-featured">Destaque</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="product-code"><?= htmlspecialchars($product['original_code'] ?? "—") ?></span>
                                </td>
                                <td>
                                    <span class="product-code"><?= htmlspecialchars($product['manufacturer_code'] ?? "—") ?></span>
                                </td>
                                <td>
                                    <?php if (!empty($product['category_name'])): ?>
                                        <span class="status-badge"><?= htmlspecialchars($product['category_name']) ?></span>
                                    <?php else: ?>
                                        <span class="status-badge status-draft">Sem categoria</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="whatsapp-badge">
                                        <i class="fab fa-whatsapp"></i> Compra pelo WhatsApp
                                    </span>
                                </td>
                                <td>
                                    <?php if ($product['status'] === 'published'): ?>
                                        <span class="status-badge status-published">Publicado</span>
                                    <?php else: ?>
                                        <span class="status-badge status-draft">Rascunho</span>
                                    <?php endif; ?>

                                    <?php if (!$product['availability']): ?>
                                        <span class="status-badge status-unavailable">Indisponível</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="table-actions">
                                        <a href="index.php?page=product_update&id=<?= $product['id'] ?>" class="table-action edit-action" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <!-- Substitua o botão de exclusão no arquivo product_list.php por este trecho -->
                                        <a href="index.php?page=product_delete&id=<?= $product['id'] ?>" class="table-action delete-action" title="Excluir">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                        <a href="<?= getProductUrl($product['slug']) ?>" class="table-action view-action" target="_blank" title="Visualizar no site">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Paginação -->
            <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <div class="pagination-info">
                        Mostrando <?= ($offset + 1) ?>-<?= min($offset + count($products), $total_items) ?> de <?= $total_items ?> produtos
                    </div>
                    <div class="pagination-links">
                        <?php
                        // Montar URL base para links de paginação mantendo os filtros
                        $pagination_url = "index.php?page=product_list";
                        if (!empty($search)) $pagination_url .= "&search=" . urlencode($search);
                        if ($category_id > 0) $pagination_url .= "&category_id=" . $category_id;
                        if (!empty($status)) $pagination_url .= "&status=" . $status;
                        if ($availability >= 0) $pagination_url .= "&availability=" . $availability;
                        ?>

                        <?php if ($current_page > 1): ?>
                            <a href="<?= $pagination_url ?>&page_num=1" class="pagination-link" title="Primeira página">
                                <i class="fas fa-angle-double-left"></i>
                            </a>
                            <a href="<?= $pagination_url ?>&page_num=<?= ($current_page - 1) ?>" class="pagination-link" title="Página anterior">
                                <i class="fas fa-angle-left"></i>
                            </a>
                        <?php endif; ?>

                        <?php
                        // Mostrar números das páginas
                        $start_page = max(1, $current_page - 2);
                        $end_page = min($total_pages, $current_page + 2);

                        // Garantir que mostramos pelo menos 5 links de página se disponíveis
                        if ($end_page - $start_page + 1 < 5) {
                            if ($start_page == 1) {
                                $end_page = min($total_pages, $start_page + 4);
                            } elseif ($end_page == $total_pages) {
                                $start_page = max(1, $end_page - 4);
                            }
                        }

                        for ($i = $start_page; $i <= $end_page; $i++):
                        ?>
                            <a href="<?= $pagination_url ?>&page_num=<?= $i ?>"
                                class="pagination-link <?= ($i == $current_page) ? 'active' : '' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>

                        <?php if ($current_page < $total_pages): ?>
                            <a href="<?= $pagination_url ?>&page_num=<?= ($current_page + 1) ?>" class="pagination-link" title="Próxima página">
                                <i class="fas fa-angle-right"></i>
                            </a>
                            <a href="<?= $pagination_url ?>&page_num=<?= $total_pages ?>" class="pagination-link" title="Última página">
                                <i class="fas fa-angle-double-right"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

        <?php endif; ?>
    </div>
</div>