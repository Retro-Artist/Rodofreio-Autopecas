<?php
// admin/pages/product_delete.php

// Verify if a product ID was provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    // Redirect back to product list if no valid ID
    header('Location: index.php?page=product_list');
    exit();
}

$product_id = (int)$_GET['id'];
$message = '';
$messageClass = '';

// Check if the product exists
try {
    // First check if the product exists
    $checkProduct = "SELECT id, title, main_picture FROM posts WHERE id = :id";
    $checkStmt = $pdo->prepare($checkProduct);
    $checkStmt->execute(['id' => $product_id]);
    $product = $checkStmt->fetch();
    
    if (!$product) {
        // If product doesn't exist, redirect back to the list
        header('Location: index.php?page=product_list');
        exit();
    }
    
    // Check if form was submitted to confirm deletion
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
        // Delete the product
        $deleteQuery = "DELETE FROM posts WHERE id = :id";
        $deleteStmt = $pdo->prepare($deleteQuery);
        if ($deleteStmt->execute(['id' => $product_id])) {
            // Set success message in session and redirect
            $_SESSION['product_message'] = "Produto '{$product['title']}' excluído com sucesso!";
            $_SESSION['product_message_class'] = 'success';
            
            // Delete product image if exists
            if (!empty($product['main_picture'])) {
                $imagePath = __DIR__ . '/../../uploads/' . $product['main_picture'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            
            header('Location: index.php?page=product_list');
            exit();
        } else {
            $message = "Erro ao excluir o produto. Por favor, tente novamente.";
            $messageClass = 'error';
        }
    }
} catch (PDOException $e) {
    error_log('Database error: ' . $e->getMessage());
    $message = "Ocorreu um erro ao processar sua solicitação. Por favor, tente novamente mais tarde.";
    $messageClass = 'error';
}
?>

<div class="product-delete-page">
    <div class="page-header">
        <h2><i class="fas fa-trash-alt"></i> Excluir Produto</h2>
        <div class="page-actions">
            <a href="index.php?page=product_list" class="back-button">
                <i class="fas fa-arrow-left"></i> Voltar para Produtos
            </a>
        </div>
    </div>
    
    <?php if ($message): ?>
        <div class="message <?= $messageClass ?>">
            <?= htmlspecialchars($message) ?>
        </div>
        
        <?php if ($messageClass === 'error'): ?>
            <div class="action-container">
                <a href="index.php?page=product_list" class="back-button">
                    <i class="fas fa-arrow-left"></i> Voltar para Lista de Produtos
                </a>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="confirm-delete-container">
            <div class="confirm-card">
                <div class="confirm-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                
                <h3 class="confirm-title">Tem certeza que deseja excluir este produto?</h3>
                
                <div class="product-preview">
                    <?php if (!empty($product['main_picture'])): ?>
                        <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($product['main_picture']) ?>" 
                             alt="<?= htmlspecialchars($product['title']) ?>" class="product-thumbnail">
                    <?php else: ?>
                        <div class="no-thumbnail"><i class="fas fa-image"></i></div>
                    <?php endif; ?>
                    <h4><?= htmlspecialchars($product['title']) ?></h4>
                </div>
                
                <p class="confirm-text">
                    Você está prestes a excluir o produto <strong><?= htmlspecialchars($product['title']) ?></strong>.
                    Esta ação não pode ser desfeita.
                </p>
                
                <div class="confirm-actions">
                    <form method="POST" class="delete-form">
                        <input type="hidden" name="confirm_delete" value="1">
                        <button type="submit" class="delete-button">
                            <i class="fas fa-trash-alt"></i> Sim, Excluir Produto
                        </button>
                    </form>
                    
                    <a href="index.php?page=product_list" class="cancel-button">
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
    
    .product-preview {
        margin: 20px 0;
        padding: 15px;
        background-color: #f8f9fa;
        border-radius: var(--border-radius);
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    
    .product-preview img {
        max-width: 150px;
        max-height: 150px;
        object-fit: contain;
        margin-bottom: 10px;
        border-radius: 4px;
    }
    
    .product-preview .no-thumbnail {
        width: 100px;
        height: 100px;
        background-color: #f0f0f0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        color: #ccc;
        margin-bottom: 10px;
        border-radius: 4px;
    }
    
    .product-preview h4 {
        margin: 0;
        color: #333;
        font-size: 18px;
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