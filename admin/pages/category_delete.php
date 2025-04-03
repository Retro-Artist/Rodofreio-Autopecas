<?php
// admin/pages/category_delete.php

// Armazenar a mensagem na sessão em vez de tentar redirecionar depois de enviar conteúdo
// Isso permitirá que reutilizemos o sistema de mensagens que já existe em category_list.php

// Verify if a category ID was provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    // Redirect back to category list if no valid ID
    header('Location: index.php?page=category_list');
    exit();
}

$category_id = (int)$_GET['id'];
$message = '';
$messageClass = '';

// Check if there are products using this category
try {
    // First check if the category exists
    $checkCategory = "SELECT id, name FROM categories WHERE id = :id";
    $checkStmt = $pdo->prepare($checkCategory);
    $checkStmt->execute(['id' => $category_id]);
    $category = $checkStmt->fetch();
    
    if (!$category) {
        // If category doesn't exist, redirect back to the list
        header('Location: index.php?page=category_list');
        exit();
    }
    
    // Check if there are products using this category
    $checkProducts = "SELECT COUNT(*) FROM posts WHERE category_id = :category_id";
    $productsStmt = $pdo->prepare($checkProducts);
    $productsStmt->execute(['category_id' => $category_id]);
    $productsCount = $productsStmt->fetchColumn();
    
    if ($productsCount > 0) {
        // Cannot delete category with associated products
        $message = "Não é possível excluir a categoria '{$category['name']}' porque existem {$productsCount} produtos associados a ela.";
        $messageClass = 'error';
    } else {
        // Check if form was submitted to confirm deletion
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
            // Check if category has children
            $checkChildren = "SELECT COUNT(*) FROM categories WHERE parent_id = :id";
            $childrenStmt = $pdo->prepare($checkChildren);
            $childrenStmt->execute(['id' => $category_id]);
            $childrenCount = $childrenStmt->fetchColumn();
            
            if ($childrenCount > 0) {
                // Cannot delete category with child categories
                $message = "Não é possível excluir a categoria '{$category['name']}' porque existem {$childrenCount} subcategorias associadas a ela.";
                $messageClass = 'error';
            } else {
                // Delete the category
                $deleteQuery = "DELETE FROM categories WHERE id = :id";
                $deleteStmt = $pdo->prepare($deleteQuery);
                if ($deleteStmt->execute(['id' => $category_id])) {
                    // Set success message in session and redirect
                    $_SESSION['category_message'] = "Categoria '{$category['name']}' excluída com sucesso!";
                    $_SESSION['category_message_class'] = 'success';
                    header('Location: index.php?page=category_list');
                    exit();
                } else {
                    $message = "Erro ao excluir a categoria. Por favor, tente novamente.";
                    $messageClass = 'error';
                }
            }
        }
    }
} catch (PDOException $e) {
    error_log('Database error: ' . $e->getMessage());
    $message = "Ocorreu um erro ao processar sua solicitação. Por favor, tente novamente mais tarde.";
    $messageClass = 'error';
}
?>

<div class="category-delete-page">
    <div class="page-header">
        <h2><i class="fas fa-trash-alt"></i> Excluir Categoria</h2>
        <div class="page-actions">
            <a href="index.php?page=category_list" class="back-button">
                <i class="fas fa-arrow-left"></i> Voltar para Categorias
            </a>
        </div>
    </div>
    
    <?php if ($message): ?>
        <div class="message <?= $messageClass ?>">
            <?= htmlspecialchars($message) ?>
        </div>
        
        <?php if ($messageClass === 'error'): ?>
            <div class="action-container">
                <a href="index.php?page=category_list" class="back-button">
                    <i class="fas fa-arrow-left"></i> Voltar para Lista de Categorias
                </a>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="confirm-delete-container">
            <div class="confirm-card">
                <div class="confirm-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                
                <h3 class="confirm-title">Tem certeza que deseja excluir esta categoria?</h3>
                
                <p class="confirm-text">
                    Você está prestes a excluir a categoria <strong><?= htmlspecialchars($category['name']) ?></strong>.
                    Esta ação não pode ser desfeita.
                </p>
                
                <div class="confirm-actions">
                    <form method="POST" class="delete-form">
                        <input type="hidden" name="confirm_delete" value="1">
                        <button type="submit" class="delete-button">
                            <i class="fas fa-trash-alt"></i> Sim, Excluir Categoria
                        </button>
                    </form>
                    
                    <a href="index.php?page=category_list" class="cancel-button">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
    .confirm-delete-container {
        max-width: 600px;
        margin: 30px auto;
    }
    
    .confirm-card {
        background-color: #fff;
        border-radius: var(--border-radius);
        padding: 30px;
        box-shadow: var(--box-shadow);
        text-align: center;
    }
    
    .confirm-icon {
        font-size: 48px;
        color: #dc3545;
        margin-bottom: 20px;
    }
    
    .confirm-title {
        font-size: 24px;
        margin-bottom: 15px;
        color: #333;
    }
    
    .confirm-text {
        font-size: 16px;
        margin-bottom: 30px;
        color: #666;
        line-height: 1.5;
    }
    
    .confirm-actions {
        display: flex;
        justify-content: center;
        gap: 15px;
    }
    
    .delete-button {
        background-color: #dc3545;
        color: white;
        border: none;
        border-radius: var(--border-radius);
        padding: 10px 20px;
        cursor: pointer;
        font-size: 16px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: background-color 0.3s ease;
    }
    
    .delete-button:hover {
        background-color: #c82333;
    }
    
    .cancel-button {
        background-color: #f8f9fa;
        color: #333;
        border: 1px solid #dee2e6;
        border-radius: var(--border-radius);
        padding: 10px 20px;
        cursor: pointer;
        font-size: 16px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: background-color 0.3s ease;
    }
    
    .cancel-button:hover {
        background-color: #e2e6ea;
    }
    
    .action-container {
        text-align: center;
        margin-top: 20px;
    }
    
    .message {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: var(--border-radius);
        background-color: #f8f9fa;
        border-left: 4px solid #ccc;
    }
    
    .message.success {
        background-color: #d4edda;
        border-left-color: #28a745;
        color: #155724;
    }
    
    .message.error {
        background-color: #f8d7da;
        border-left-color: #dc3545;
        color: #721c24;
    }
</style>