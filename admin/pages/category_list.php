<?php
// admin/pages/category_list.php

// Mensagem de feedback
$message = '';
$messageClass = '';

// Verificar se há mensagem da sessão (do category_delete.php)
if (isset($_SESSION['category_message'])) {
    $message = $_SESSION['category_message'];
    $messageClass = $_SESSION['category_message_class'] ?? 'info';
    
    // Limpar a mensagem da sessão para evitar exibi-la novamente ao atualizar
    unset($_SESSION['category_message']);
    unset($_SESSION['category_message_class']);
}

// Processar exclusão via formulário se solicitado
if (isset($_POST['delete_category'])) {
    $category_id = filter_input(INPUT_POST, 'category_id', FILTER_VALIDATE_INT);
    
    if ($category_id) {
        // Redirecionar para a página dedicada de exclusão
        header("Location: index.php?page=category_delete&id={$category_id}");
        exit();
    }
}

// Buscar todas as categorias
try {
    $query = "SELECT c.*, 
              (SELECT COUNT(*) FROM posts WHERE category_id = c.id) as product_count,
              p.name as parent_name
              FROM categories c
              LEFT JOIN categories p ON c.parent_id = p.id
              ORDER BY c.name";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $categories = $stmt->fetchAll();
} catch (PDOException $e) {
    $message = "Erro ao buscar categorias: " . $e->getMessage();
    $messageClass = 'error';
    error_log($e->getMessage());
    $categories = [];
}
?>

<div class="category-list-page">
    <div class="page-header">
        <h2><i class="fas fa-tags"></i> Gerenciar Categorias</h2>
        <div class="page-actions">
            <a href="index.php?page=category_create" class="action-button">
                <i class="fas fa-plus"></i> Nova Categoria
            </a>
        </div>
    </div>
    
    <?php if ($message): ?>
        <div class="message <?= $messageClass ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>
    
    <div class="data-container">
        <?php if (empty($categories)): ?>
            <div class="empty-state">
                <i class="fas fa-tag"></i>
                <p>Nenhuma categoria cadastrada</p>
                <a href="index.php?page=category_create" class="action-button">Criar Primeira Categoria</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Slug</th>
                            <th>Categoria Pai</th>
                            <th>Produtos</th>
                            <th width="120">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $category): ?>
                            <tr>
                                <td><?= htmlspecialchars($category['name']) ?></td>
                                <td><span class="slug-text"><?= htmlspecialchars($category['slug']) ?></span></td>
                                <td>
                                    <?php if ($category['parent_id']): ?>
                                        <?= htmlspecialchars($category['parent_name']) ?>
                                    <?php else: ?>
                                        <span class="status-badge">Categoria Principal</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="product-count">
                                        <span class="count-number"><?= $category['product_count'] ?></span>
                                        <?php if ($category['product_count'] > 0): ?>
                                            <a href="index.php?page=product_list&category_id=<?= $category['id'] ?>" class="view-link">
                                                <i class="fas fa-external-link-alt"></i> Ver produtos
                                            </a>
                                            <a href="<?= getCategoryUrl($category['slug']) ?>" class="view-link" target="_blank">
                                                <i class="fas fa-eye"></i> Ver no site
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="table-actions">
                                        <a href="index.php?page=category_update&id=<?= $category['id'] ?>" class="table-action edit-action" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if ($category['product_count'] > 0): ?>
                                            <!-- Desabilitar botão de exclusão se tiver produtos associados -->
                                            <button class="table-action delete-action" disabled title="Remova os produtos desta categoria primeiro">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        <?php else: ?>
                                            <!-- Link para a página de confirmação de exclusão -->
                                            <a href="index.php?page=category_delete&id=<?= $category['id'] ?>" class="table-action delete-action" title="Excluir">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>