<?php
// admin/pages/update.php

$message = '';
$messageClass = '';
$post = null;

// Get post ID and validate
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: ?page=home');
    exit();
}

// Get available categories
try {
    $categoryQuery = "SELECT id, name FROM categories ORDER BY name";
    $categoryStmt = $pdo->prepare($categoryQuery);
    $categoryStmt->execute();
    $categories = $categoryStmt->fetchAll();
} catch (PDOException $e) {
    error_log($e->getMessage());
    $categories = [];
}

// Get existing product data
try {
    $query = "SELECT * FROM posts WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['id' => $id]);
    $product = $stmt->fetch();

    if (!$product) {
        header('Location: ?page=home');
        exit();
    }
} catch (PDOException $e) {
    error_log($e->getMessage());
    header('Location: ?page=home');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $status = $_POST['status'] ?? 'draft';
    $tags = trim($_POST['tags'] ?? '');
    $featured = isset($_POST['featured']) ? 1 : 0;
    $availability = isset($_POST['availability']) ? 1 : 0;
    $category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
    $original_code = trim($_POST['original_code'] ?? '');
    $manufacturer_code = trim($_POST['manufacturer_code'] ?? '');

    // Generate a new slug based on the title
    $slug = createSlug($title);

    // Handle file upload if new image is provided
    $mainPicture = $product['main_picture']; // Keep existing picture by default
    if (isset($_FILES['main_picture']) && $_FILES['main_picture']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../../uploads/';
        $fileExtension = strtolower(pathinfo($_FILES['main_picture']['name'], PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($fileExtension, $allowedTypes)) {
            $message = 'Tipo de arquivo inválido. Apenas JPG, PNG e GIF são permitidos.';
            $messageClass = 'error';
        } else {
            $fileName = uniqid('product_') . '.' . $fileExtension;
            $uploadFile = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['main_picture']['tmp_name'], $uploadFile)) {
                // Delete old image if exists
                if ($product['main_picture'] && file_exists($uploadDir . $product['main_picture'])) {
                    unlink($uploadDir . $product['main_picture']);
                }
                $mainPicture = $fileName;
            } else {
                $message = 'Erro ao fazer upload do arquivo.';
                $messageClass = 'error';
            }
        }
    }

    // Validate required fields
    if (empty($title) || empty($description)) {
        $message = 'Por favor, preencha todos os campos obrigatórios.';
        $messageClass = 'error';
    } else {
        try {
            // Verificar se o slug já existe (ignorando o produto atual)
            $checkSlugQuery = "SELECT COUNT(*) FROM posts WHERE slug = :slug AND id != :id";
            $checkStmt = $pdo->prepare($checkSlugQuery);
            $checkStmt->execute([
                'slug' => $slug,
                'id' => $id
            ]);

            if ($checkStmt->fetchColumn() > 0) {
                // Slug já existe, notificar usuário
                $message = "Produto \"" . htmlspecialchars($title) . "\" já existe. Por favor, use um nome diferente.";
                $messageClass = 'error';
            } else {
                // Update product
                $query = "UPDATE posts SET 
            title = :title,
            content = :content,
            description = :description,
            main_picture = :main_picture,
            slug = :slug,
            status = :status,
            tags = :tags,
            featured = :featured,
            category_id = :category_id,
            original_code = :original_code,
            manufacturer_code = :manufacturer_code,
            availability = :availability
            WHERE id = :id";

                $stmt = $pdo->prepare($query);
                $success = $stmt->execute([
                    'title' => $title,
                    'content' => $content,
                    'description' => $description,
                    'main_picture' => $mainPicture,
                    'slug' => $slug,
                    'status' => $status,
                    'tags' => $tags,
                    'featured' => $featured,
                    'category_id' => $category_id,
                    'original_code' => $original_code,
                    'manufacturer_code' => $manufacturer_code,
                    'availability' => $availability,
                    'id' => $id
                ]);

                if ($success) {
                    $message = 'Produto atualizado com sucesso!';
                    $messageClass = 'success';
                } else {
                    $message = 'Erro ao atualizar produto.';
                    $messageClass = 'error';
                }
            }
        } catch (PDOException $e) {
            // Se ainda assim ocorrer erro de duplicação (caso remoto)
            if ($e->getCode() == 23000 && strpos($e->getMessage(), 'Duplicate entry') !== false && strpos($e->getMessage(), 'posts.slug') !== false) {
                $message = "Produto \"" . htmlspecialchars($title) . "\" já existe. Por favor, use um nome diferente.";
            } else {
                $message = 'Erro ao atualizar produto: ' . $e->getMessage();
            }
            $messageClass = 'error';
            error_log($e->getMessage());
        }
    }
}
?>

<div class="edit-post">
    <h2>Editar Produto</h2>

    <?php if ($message): ?>
        <div class="message <?= $messageClass ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <div class="product-actions-top">
        <a href="<?= getProductUrl($product['slug']) ?>" class="view-site-link" target="_blank">
            <i class="fas fa-external-link-alt"></i> Ver no site
        </a>
    </div>

    <form method="POST" class="post-form" enctype="multipart/form-data">
        <div class="form-row">
            <div class="form-group">
                <label for="title">Nome do Produto *</label>
                <input type="text" id="title" name="title" required
                    value="<?= htmlspecialchars($product['title']) ?>">
            </div>

            <div class="form-group">
                <label for="original_code">Código Original</label>
                <input type="text" id="original_code" name="original_code"
                    value="<?= htmlspecialchars($product['original_code'] ?? '') ?>">
                <small>Código original do fabricante do veículo</small>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="manufacturer_code">Código Fabricante</label>
                <input type="text" id="manufacturer_code" name="manufacturer_code"
                    value="<?= htmlspecialchars($product['manufacturer_code'] ?? '') ?>">
                <small>Código do fabricante da peça</small>
            </div>

            <div class="form-group">
                <label for="category_id">Categoria</label>
                <select id="category_id" name="category_id">
                    <option value="">Selecione uma categoria</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>" <?= ($product['category_id'] == $category['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($category['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="tags">Tags</label>
                <input type="text" id="tags" name="tags"
                    value="<?= htmlspecialchars($product['tags']) ?>"
                    placeholder="Separadas por vírgulas">
                <small>Ex: freio, suspensão, motor, filtro</small>
            </div>
        </div>

        <div class="form-group">
            <label for="description">Descrição Curta *</label>
            <textarea id="description" name="description" required rows="3"><?= htmlspecialchars($product['description']) ?></textarea>
            <small>Breve resumo do produto (aparece nas listagens)</small>
        </div>

        <div class="form-group">
            <label for="content">Descrição Completa</label>
            <textarea id="content" name="content" rows="10"><?= htmlspecialchars($product['content']) ?></textarea>
            <small>Descrição detalhada do produto, especificações, compatibilidade, etc.</small>
        </div>

        <div class="form-group">
            <label for="main_picture">Imagem do Produto</label>
            <?php if ($product['main_picture']): ?>
                <div class="current-image">
                    <img src="<?= BASE_URL . '/uploads/' . $product['main_picture'] ?>"
                        alt="Imagem atual" style="max-width: 200px;">
                    <p>Imagem atual: <?= htmlspecialchars($product['main_picture']) ?></p>
                </div>
            <?php endif; ?>
            <input type="file" id="main_picture" name="main_picture" accept="image/*">
            <small>Formatos suportados: JPG, PNG, GIF. Deixe vazio para manter imagem atual.</small>
        </div>

        <div class="form-row">
            <div class="form-group checkbox-group">
                <input type="checkbox" id="featured" name="featured" value="1"
                    <?= $product['featured'] ? 'checked' : '' ?>>
                <label for="featured">Produto em Destaque</label>
                <small>Produtos em destaque aparecem na página inicial</small>
            </div>

            <div class="form-group checkbox-group">
                <input type="checkbox" id="availability" name="availability" value="1"
                    <?= $product['availability'] ? 'checked' : '' ?>>
                <label for="availability">Produto Disponível</label>
                <small>Desmarque se o produto estiver temporariamente indisponível</small>
            </div>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select id="status" name="status">
                <option value="draft" <?= $product['status'] === 'draft' ? 'selected' : '' ?>>Rascunho</option>
                <option value="published" <?= $product['status'] === 'published' ? 'selected' : '' ?>>Publicado</option>
            </select>
            <small>Apenas produtos publicados aparecem no site</small>
        </div>

        <div class="form-actions">
            <button type="submit" class="submit-button">Atualizar Produto</button>
            <a href="?page=home" class="cancel-button">Cancelar</a>
        </div>
    </form>
</div>

<style>
    /* Additional styling for the View on Site button */
    .product-actions-top {
        margin-bottom: 20px;
        padding: 10px 0;
        border-bottom: 1px solid #eee;
    }

    .view-site-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background-color: #f0f8ff;
        color: #0066cc;
        padding: 8px 15px;
        border-radius: var(--border-radius);
        text-decoration: none;
        font-size: 14px;
        transition: all 0.3s;
    }

    .view-site-link:hover {
        background-color: #e1f0ff;
        transform: translateY(-2px);
    }

    /* Form styling */
    .form-row {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
    }

    .form-row .form-group {
        flex: 1;
        margin-bottom: 0;
    }

    .input-prefix {
        position: relative;
    }

    .input-prefix .prefix {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #666;
    }

    .input-prefix input {
        padding-left: 30px;
    }

    .current-image {
        margin-bottom: 15px;
        padding: 10px;
        background-color: #f8f9fa;
        border-radius: 4px;
    }

    @media (max-width: 768px) {
        .form-row {
            flex-direction: column;
            gap: 15px;
        }
    }
</style>