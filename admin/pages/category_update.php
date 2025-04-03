<?php
// admin/pages/category_update.php

$message = '';
$messageClass = '';

// Obter ID da categoria
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: index.php?page=category_list');
    exit();
}

// Buscar categorias para lista de pais (excluindo a própria categoria e descendentes)
try {
    $parentQuery = "SELECT id, name FROM categories WHERE id != :id ORDER BY name";
    $parentStmt = $pdo->prepare($parentQuery);
    $parentStmt->execute(['id' => $id]);
    $parentCategories = $parentStmt->fetchAll();
} catch (PDOException $e) {
    error_log($e->getMessage());
    $parentCategories = [];
}

// Buscar dados da categoria atual
try {
    $query = "SELECT * FROM categories WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['id' => $id]);
    $category = $stmt->fetch();
    
    if (!$category) {
        header('Location: index.php?page=category_list');
        exit();
    }
} catch (PDOException $e) {
    error_log($e->getMessage());
    header('Location: index.php?page=category_list');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obter dados do formulário
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $parent_id = !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null;
    
    // Verificar que parent_id não é o próprio id (evitar ciclos)
    if ($parent_id == $id) {
        $parent_id = null;
    }
    
    $slug = createSlug($name);
    
    // Validar campos
    if (empty($name)) {
        $message = 'O nome da categoria é obrigatório.';
        $messageClass = 'error';
    } else {
        try {
            // Verificar se slug já existe (ignorando a categoria atual)
            $checkQuery = "SELECT COUNT(*) FROM categories WHERE slug = :slug AND id != :id";
            $checkStmt = $pdo->prepare($checkQuery);
            $checkStmt->execute([
                'slug' => $slug,
                'id' => $id
            ]);
            
            if ($checkStmt->fetchColumn() > 0) {
                $message = 'Já existe outra categoria com esse nome. Por favor, escolha outro nome.';
                $messageClass = 'error';
            } else {
                // Atualizar categoria
                $query = "UPDATE categories SET 
                          name = :name, 
                          slug = :slug, 
                          description = :description, 
                          parent_id = :parent_id 
                          WHERE id = :id";
                $stmt = $pdo->prepare($query);
                $stmt->execute([
                    'name' => $name,
                    'slug' => $slug,
                    'description' => $description,
                    'parent_id' => $parent_id,
                    'id' => $id
                ]);
                
                $message = 'Categoria atualizada com sucesso!';
                $messageClass = 'success';
                
                // Atualizar a variável de categoria com novos valores
                $category = [
                    'id' => $id,
                    'name' => $name,
                    'slug' => $slug,
                    'description' => $description,
                    'parent_id' => $parent_id,
                    'created_at' => $category['created_at'] // Mantém a data original
                ];
            }
        } catch (PDOException $e) {
            $message = 'Erro ao atualizar categoria: ' . $e->getMessage();
            $messageClass = 'error';
            error_log($e->getMessage());
        }
    }
}

// Obter contagem de produtos para esta categoria
try {
    $productCountQuery = "SELECT COUNT(*) FROM posts WHERE category_id = :category_id";
    $productCountStmt = $pdo->prepare($productCountQuery);
    $productCountStmt->execute(['category_id' => $id]);
    $productCount = $productCountStmt->fetchColumn();
} catch (PDOException $e) {
    error_log($e->getMessage());
    $productCount = 0;
}
?>

<div class="category-update-page">
    <div class="page-header">
        <h2><i class="fas fa-edit"></i> Editar Categoria</h2>
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
    <?php endif; ?>
    
    <div class="form-container">
        <!-- Informações resumidas da categoria -->
        <div class="category-summary">
            <div class="summary-item">
                <span class="summary-label">ID:</span>
                <span class="summary-value"><?= $id ?></span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Slug atual:</span>
                <span class="summary-value slug-value"><?= htmlspecialchars($category['slug']) ?></span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Produtos:</span>
                <span class="summary-value">
                    <span class="count-badge"><?= $productCount ?></span>
                    <?php if ($productCount > 0): ?>
                        <a href="index.php?page=product_list&category_id=<?= $id ?>" class="view-link">
                            <i class="fas fa-external-link-alt"></i> Ver produtos
                        </a>
                    <?php endif; ?>
                </span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Criada em:</span>
                <span class="summary-value">
                    <?php if (!empty($category['created_at'])): ?>
                        <?= (new DateTime($category['created_at']))->format('d/m/Y H:i') ?>
                    <?php else: ?>
                        Data não disponível
                    <?php endif; ?>
                </span>
            </div>
        </div>

        <form method="POST" class="category-form">
            <div class="form-group">
                <label for="name">Nome da Categoria *</label>
                <input type="text" id="name" name="name" required 
                       value="<?= htmlspecialchars($category['name']) ?>">
                <small>Este nome será exibido no site para os clientes</small>
            </div>
            
            <div class="form-group">
                <label for="parent_id">Categoria Pai</label>
                <select id="parent_id" name="parent_id">
                    <option value="">Nenhuma (Categoria Principal)</option>
                    <?php foreach ($parentCategories as $parent): ?>
                        <?php if ($parent['id'] != $id): // Evitar que a categoria seja pai dela mesma ?>
                            <option value="<?= $parent['id'] ?>" <?= ($category['parent_id'] == $parent['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($parent['name']) ?>
                            </option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
                <small>Opcional. Selecione a categoria pai se esta for uma subcategoria.</small>
            </div>
            
            <div class="form-group">
                <label for="description">Descrição</label>
                <textarea id="description" name="description" rows="4"><?= htmlspecialchars($category['description'] ?? '') ?></textarea>
                <small>Uma breve descrição da categoria (opcional)</small>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="submit-button">
                    <i class="fas fa-save"></i> Atualizar Categoria
                </button>
                <a href="index.php?page=category_list" class="cancel-button">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>