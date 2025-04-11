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

    // Get additional images
    $imagesQuery = "SELECT * FROM product_images WHERE product_id = :product_id ORDER BY display_order";
    $imagesStmt = $pdo->prepare($imagesQuery);
    $imagesStmt->execute(['product_id' => $id]);
    $additionalImages = $imagesStmt->fetchAll();

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

    // Process deleted images if any
    $deletedImages = isset($_POST['deleted_images']) ? explode(',', $_POST['deleted_images']) : [];
    
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
            // Begin transaction
            $pdo->beginTransaction();
            
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
                $pdo->rollBack();
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

                // Process deleted images
                if (!empty($deletedImages)) {
                    $uploadDir = __DIR__ . '/../../uploads/';
                    foreach ($deletedImages as $imageId) {
                        if (!empty($imageId)) {
                            // Get the image path first
                            $getImageQuery = "SELECT image_path FROM product_images WHERE id = :id";
                            $getImageStmt = $pdo->prepare($getImageQuery);
                            $getImageStmt->execute(['id' => $imageId]);
                            $imagePath = $getImageStmt->fetchColumn();
                            
                            // Delete the record
                            $deleteImageQuery = "DELETE FROM product_images WHERE id = :id";
                            $deleteImageStmt = $pdo->prepare($deleteImageQuery);
                            $deleteImageStmt->execute(['id' => $imageId]);
                            
                            // Delete the file
                            if ($imagePath && file_exists($uploadDir . $imagePath)) {
                                unlink($uploadDir . $imagePath);
                            }
                        }
                    }
                }
                
                // Handle additional images if present
                if (!empty($_FILES['additional_images']['name'][0])) {
                    $additionalImages = $_FILES['additional_images'];
                    $imageCount = count($additionalImages['name']);
                    $uploadDir = __DIR__ . '/../../uploads/';
                    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
                    
                    // Get the current highest display order
                    $orderQuery = "SELECT MAX(display_order) FROM product_images WHERE product_id = :product_id";
                    $orderStmt = $pdo->prepare($orderQuery);
                    $orderStmt->execute(['product_id' => $id]);
                    $maxOrder = (int)$orderStmt->fetchColumn();
                    
                    // Insert query for additional images
                    $imageQuery = "INSERT INTO product_images (product_id, image_path, display_order) VALUES (?, ?, ?)";
                    $imageStmt = $pdo->prepare($imageQuery);
                    
                    for ($i = 0; $i < $imageCount; $i++) {
                        if ($additionalImages['error'][$i] === UPLOAD_ERR_OK) {
                            $fileExtension = strtolower(pathinfo($additionalImages['name'][$i], PATHINFO_EXTENSION));
                            
                            if (in_array($fileExtension, $allowedTypes)) {
                                $fileName = uniqid('product_additional_') . '.' . $fileExtension;
                                $uploadFile = $uploadDir . $fileName;
                                
                                if (move_uploaded_file($additionalImages['tmp_name'][$i], $uploadFile)) {
                                    // Insert into product_images table with next display order
                                    $imageStmt->execute([$id, $fileName, $maxOrder + $i + 1]);
                                }
                            }
                        }
                    }
                }

                // Commit the transaction
                $pdo->commit();
                
                if ($success) {
                    $message = 'Produto atualizado com sucesso!';
                    $messageClass = 'success';
                    
                    // Refresh the product and images data
                    $stmt = $pdo->prepare($query);
                    $stmt->execute(['id' => $id]);
                    $product = $stmt->fetch();
                    
                    $imagesStmt = $pdo->prepare($imagesQuery);
                    $imagesStmt->execute(['product_id' => $id]);
                    $additionalImages = $imagesStmt->fetchAll();
                } else {
                    $message = "Erro ao atualizar o produto. Por favor, tente novamente.";
                    $messageClass = 'error';
                }
            }
        } catch (PDOException $e) {
            // Rollback on error
            $pdo->rollBack();
            
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
            <label for="main_picture">Imagem Principal</label>
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

        <div class="form-group">
        <label for="additional_images">Imagens Adicionais</label>
            <input type="file" id="additional_images" name="additional_images[]" accept="image/*" multiple>
            <small>Você pode selecionar várias imagens adicionais para o produto. Formatos suportados: JPG, PNG, GIF.</small>
            
            <?php if (!empty($additionalImages)): ?>
                <div class="existing-images">
                    <h4>Imagens Atuais</h4>
                    <div class="image-preview-container">
                        <?php foreach ($additionalImages as $image): ?>
                            <div class="image-preview" data-id="<?= $image['id'] ?>">
                                <img src="<?= BASE_URL . '/uploads/' . $image['image_path'] ?>" alt="Imagem adicional">
                                <span class="remove-image" title="Remover imagem"><i class="fas fa-times"></i></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <div id="image-preview" class="image-preview-container"></div>
            <input type="hidden" name="deleted_images" id="deleted-images" value="">
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
            <a href="?page=product_list" class="cancel-button">Cancelar</a>
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
    
    /* Image gallery */
    .existing-images h4 {
        margin: 15px 0 10px;
        font-size: 16px;
        color: #333;
    }
    
    .image-preview-container {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 15px;
    }
    
    .image-preview {
        width: 100px;
        height: 100px;
        border-radius: 4px;
        border: 1px solid #ddd;
        position: relative;
        overflow: hidden;
    }
    
    .image-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 4px;
    }
    
    .image-preview .remove-image {
        position: absolute;
        top: -8px;
        right: -8px;
        background-color: #dc3545;
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 10px;
        opacity: 0;
        transition: opacity 0.3s;
    }
    
    .image-preview:hover .remove-image {
        opacity: 1;
    }
    
    .image-preview.to-delete {
        opacity: 0.5;
    }

    @media (max-width: 768px) {
        .form-row {
            flex-direction: column;
            gap: 15px;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Image preview for additional images
    const additionalImagesInput = document.getElementById('additional_images');
    const imagePreviewContainer = document.getElementById('image-preview');
    const deletedImagesInput = document.getElementById('deleted-images');
    let deletedImages = [];
    
    if (additionalImagesInput && imagePreviewContainer) {
        additionalImagesInput.addEventListener('change', function() {
            imagePreviewContainer.innerHTML = '';
            
            if (this.files) {
                for (let i = 0; i < this.files.length; i++) {
                    const file = this.files[i];
                    
                    if (file.type.match('image.*')) {
                        const reader = new FileReader();
                        
                        reader.onload = function(e) {
                            const previewDiv = document.createElement('div');
                            previewDiv.className = 'image-preview';
                            
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.alt = 'Preview';
                            
                            previewDiv.appendChild(img);
                            imagePreviewContainer.appendChild(previewDiv);
                        }
                        
                        reader.readAsDataURL(file);
                    }
                }
            }
        });
    }
    
    // Handle removal of existing images
    const removeButtons = document.querySelectorAll('.remove-image');
    if (removeButtons.length > 0) {
        removeButtons.forEach(button => {
            button.addEventListener('click', function() {
                const imagePreview = this.closest('.image-preview');
                const imageId = imagePreview.dataset.id;
                
                // Toggle deletion
                if (imagePreview.classList.contains('to-delete')) {
                    imagePreview.classList.remove('to-delete');
                    deletedImages = deletedImages.filter(id => id !== imageId);
                } else {
                    imagePreview.classList.add('to-delete');
                    deletedImages.push(imageId);
                }
                
                // Update hidden input
                deletedImagesInput.value = deletedImages.join(',');
            });
        });
    }
});
</script>