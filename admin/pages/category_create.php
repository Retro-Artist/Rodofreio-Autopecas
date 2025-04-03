<?php
// admin/pages/category_create.php

$message = '';
$messageClass = '';
$redirect = false; // Flag para redirecionamento

// Buscar categorias para lista de pais
try {
    $parentQuery = "SELECT id, name FROM categories ORDER BY name";
    $parentStmt = $pdo->prepare($parentQuery);
    $parentStmt->execute();
    $parentCategories = $parentStmt->fetchAll();
} catch (PDOException $e) {
    error_log($e->getMessage());
    $parentCategories = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obter dados do formulário
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $parent_id = !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null;
    $slug = createSlug($name);
    
    // Validar campos
    if (empty($name)) {
        $message = 'O nome da categoria é obrigatório.';
        $messageClass = 'error';
    } else {
        try {
            // Verificar se slug já existe
            $checkQuery = "SELECT COUNT(*) FROM categories WHERE slug = :slug";
            $checkStmt = $pdo->prepare($checkQuery);
            $checkStmt->execute(['slug' => $slug]);
            
            if ($checkStmt->fetchColumn() > 0) {
                $message = 'Já existe uma categoria com esse nome. Por favor, escolha outro nome.';
                $messageClass = 'error';
            } else {
                // Inserir categoria
                $query = "INSERT INTO categories (name, slug, description, parent_id) 
                          VALUES (:name, :slug, :description, :parent_id)";
                $stmt = $pdo->prepare($query);
                $stmt->execute([
                    'name' => $name,
                    'slug' => $slug,
                    'description' => $description,
                    'parent_id' => $parent_id
                ]);
                
                $message = 'Categoria criada com sucesso!';
                $messageClass = 'success';
                
                // Definir flag de redirecionamento
                $redirect = true;
            }
        } catch (PDOException $e) {
            $message = 'Erro ao criar categoria: ' . $e->getMessage();
            $messageClass = 'error';
            error_log($e->getMessage());
        }
    }
}

// Se a flag de redirecionamento estiver ativa, usamos JavaScript
if ($redirect) {
    echo "<script>window.location.href='index.php?page=category_list';</script>";
    exit();
}
?>

<div class="category-create-page">
    <div class="page-header">
        <h2><i class="fas fa-plus"></i> Nova Categoria</h2>
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
        <form method="POST" class="category-form">
            <div class="form-group">
                <label for="name">Nome da Categoria *</label>
                <input type="text" id="name" name="name" required 
                       value="<?= htmlspecialchars($name ?? $_POST['name'] ?? '') ?>">
                <small>Ex: Freios, Suspensão, Filtros</small>
            </div>
            
            <div class="form-group">
                <label for="parent_id">Categoria Pai</label>
                <select id="parent_id" name="parent_id">
                    <option value="">Nenhuma (Categoria Principal)</option>
                    <?php foreach ($parentCategories as $parent): ?>
                        <option value="<?= $parent['id'] ?>" <?= (isset($_POST['parent_id']) && $_POST['parent_id'] == $parent['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($parent['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <small>Opcional. Selecione a categoria pai se esta for uma subcategoria.</small>
            </div>
            
            <div class="form-group">
                <label for="description">Descrição</label>
                <textarea id="description" name="description" rows="4"><?= htmlspecialchars($description ?? $_POST['description'] ?? '') ?></textarea>
                <small>Uma breve descrição da categoria (opcional)</small>
            </div>
            
            <?php if (!empty($slug)): ?>
            <div class="form-group">
                <label>Slug Gerado</label>
                <div class="slug-preview"><?= htmlspecialchars($slug) ?></div>
                <small>O slug é gerado automaticamente a partir do nome e usado nas URLs.</small>
            </div>
            <?php endif; ?>
            
            <div class="form-actions">
                <button type="submit" class="submit-button">
                    <i class="fas fa-save"></i> Criar Categoria
                </button>
                <a href="index.php?page=category_list" class="cancel-button">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>