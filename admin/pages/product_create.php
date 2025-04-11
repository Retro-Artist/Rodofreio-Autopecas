<?php
// admin/pages/product_create.php

$message = '';
$messageClass = '';
$redirect = false; // Variável para controlar o redirecionamento

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
    $slug = createSlug($title);

    // Handle main image upload with image compression
    $mainPicture = null;
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

            // Apply image compression based on file type
            $sourceImage = null;
            $targetWidth = 1200; // Maximum width for product images
            $quality = 85; // Compression quality (0-100)

            switch ($fileExtension) {
                case 'jpg':
                case 'jpeg':
                    $sourceImage = imagecreatefromjpeg($_FILES['main_picture']['tmp_name']);
                    break;
                case 'png':
                    $sourceImage = imagecreatefrompng($_FILES['main_picture']['tmp_name']);
                    break;
                case 'gif':
                    $sourceImage = imagecreatefromgif($_FILES['main_picture']['tmp_name']);
                    break;
            }

            if ($sourceImage) {
                // Get original dimensions
                $width = imagesx($sourceImage);
                $height = imagesy($sourceImage);

                // Calculate new dimensions if needed
                if ($width > $targetWidth) {
                    $ratio = $height / $width;
                    $newWidth = $targetWidth;
                    $newHeight = $targetWidth * $ratio;

                    // Create a new image with the calculated dimensions
                    $newImage = imagecreatetruecolor($newWidth, $newHeight);

                    // Handle transparency for PNG images
                    if ($fileExtension === 'png') {
                        imagealphablending($newImage, false);
                        imagesavealpha($newImage, true);
                        $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
                        imagefilledrectangle($newImage, 0, 0, $newWidth, $newHeight, $transparent);
                    }

                    // Resize the image
                    imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

                    // Save the resized image
                    switch ($fileExtension) {
                        case 'jpg':
                        case 'jpeg':
                            imagejpeg($newImage, $uploadFile, $quality);
                            break;
                        case 'png':
                            // PNG quality is 0-9, convert from 0-100
                            $pngQuality = 9 - round(($quality / 100) * 9);
                            imagepng($newImage, $uploadFile, $pngQuality);
                            break;
                        case 'gif':
                            imagegif($newImage, $uploadFile);
                            break;
                    }

                    // Free up memory
                    imagedestroy($newImage);
                    imagedestroy($sourceImage);
                    
                    $mainPicture = $fileName;
                } else {
                    // If the image is already smaller than our target width, just copy it
                    if (move_uploaded_file($_FILES['main_picture']['tmp_name'], $uploadFile)) {
                        $mainPicture = $fileName;
                    } else {
                        $message = 'Erro ao fazer upload do arquivo.';
                        $messageClass = 'error';
                    }
                }
            } else {
                // Fallback to direct upload without compression
                if (move_uploaded_file($_FILES['main_picture']['tmp_name'], $uploadFile)) {
                    $mainPicture = $fileName;
                } else {
                    $message = 'Erro ao fazer upload do arquivo.';
                    $messageClass = 'error';
                }
            }
        }
    }

    // Validate required fields
    if (empty($title) || empty($description)) {
        $message = 'Por favor, preencha todos os campos obrigatórios.';
        $messageClass = 'error';
    } else {
        try {
            // Verificar se o slug já existe
            $checkSlugQuery = "SELECT COUNT(*) FROM posts WHERE slug = :slug";
            $checkStmt = $pdo->prepare($checkSlugQuery);
            $checkStmt->execute(['slug' => $slug]);
            
            if ($checkStmt->fetchColumn() > 0) {
                // Slug já existe, notificar usuário
                $message = "Produto \"" . htmlspecialchars($title) . "\" já existe. Por favor, use um nome diferente.";
                $messageClass = 'error';
            } else {
                // Begin transaction
                $pdo->beginTransaction();

                // Inserir produto
                $query = "INSERT INTO posts (
                    title, content, description, main_picture, 
                    slug, status, tags, featured, user_id,
                    category_id, original_code, manufacturer_code, availability
                ) VALUES (
                    :title, :content, :description, :main_picture,
                    :slug, :status, :tags, :featured, :user_id,
                    :category_id, :original_code, :manufacturer_code, :availability
                )";

                $stmt = $pdo->prepare($query);
                $stmt->execute([
                    'title' => $title,
                    'content' => $content,
                    'description' => $description,
                    'main_picture' => $mainPicture,
                    'slug' => $slug,
                    'status' => $status,
                    'tags' => $tags,
                    'featured' => $featured,
                    'user_id' => $_SESSION['admin_id'],
                    'category_id' => $category_id,
                    'original_code' => $original_code,
                    'manufacturer_code' => $manufacturer_code,
                    'availability' => $availability
                ]);

                // Obter o ID do produto recém-inserido
                $new_product_id = $pdo->lastInsertId();

                // Handle additional images if present (limited to 5)
                if (!empty($_FILES['additional_images']['name'][0])) {
                    $additionalImages = $_FILES['additional_images'];
                    $imageCount = count($additionalImages['name']);
                    $maxAdditionalImages = 5; // Maximum 5 additional images (plus 1 main = 6 total)
                    
                    // Limit the number of images to process
                    $imageCount = min($imageCount, $maxAdditionalImages);
                    
                    $uploadDir = __DIR__ . '/../../uploads/';
                    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
                    
                    // Insert query for additional images
                    $imageQuery = "INSERT INTO product_images (product_id, image_path, display_order) VALUES (?, ?, ?)";
                    $imageStmt = $pdo->prepare($imageQuery);
                    
                    for ($i = 0; $i < $imageCount; $i++) {
                        if ($additionalImages['error'][$i] === UPLOAD_ERR_OK) {
                            $fileExtension = strtolower(pathinfo($additionalImages['name'][$i], PATHINFO_EXTENSION));
                            
                            if (in_array($fileExtension, $allowedTypes)) {
                                $fileName = uniqid('product_additional_') . '.' . $fileExtension;
                                $uploadFile = $uploadDir . $fileName;
                                
                                // Apply image compression for additional images
                                $sourceImage = null;
                                $targetWidth = 1200; // Same settings as main image
                                $quality = 85;
                                
                                switch ($fileExtension) {
                                    case 'jpg':
                                    case 'jpeg':
                                        $sourceImage = imagecreatefromjpeg($additionalImages['tmp_name'][$i]);
                                        break;
                                    case 'png':
                                        $sourceImage = imagecreatefrompng($additionalImages['tmp_name'][$i]);
                                        break;
                                    case 'gif':
                                        $sourceImage = imagecreatefromgif($additionalImages['tmp_name'][$i]);
                                        break;
                                }
                                
                                if ($sourceImage) {
                                    // Get original dimensions
                                    $width = imagesx($sourceImage);
                                    $height = imagesy($sourceImage);
                                    
                                    // Calculate new dimensions if needed
                                    if ($width > $targetWidth) {
                                        $ratio = $height / $width;
                                        $newWidth = $targetWidth;
                                        $newHeight = $targetWidth * $ratio;
                                        
                                        // Create a new image with the calculated dimensions
                                        $newImage = imagecreatetruecolor($newWidth, $newHeight);
                                        
                                        // Handle transparency for PNG images
                                        if ($fileExtension === 'png') {
                                            imagealphablending($newImage, false);
                                            imagesavealpha($newImage, true);
                                            $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
                                            imagefilledrectangle($newImage, 0, 0, $newWidth, $newHeight, $transparent);
                                        }
                                        
                                        // Resize the image
                                        imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                                        
                                        // Save the resized image
                                        switch ($fileExtension) {
                                            case 'jpg':
                                            case 'jpeg':
                                                imagejpeg($newImage, $uploadFile, $quality);
                                                break;
                                            case 'png':
                                                // PNG quality is 0-9, convert from 0-100
                                                $pngQuality = 9 - round(($quality / 100) * 9);
                                                imagepng($newImage, $uploadFile, $pngQuality);
                                                break;
                                            case 'gif':
                                                imagegif($newImage, $uploadFile);
                                                break;
                                        }
                                        
                                        // Free up memory
                                        imagedestroy($newImage);
                                        imagedestroy($sourceImage);
                                        
                                        // Insert into product_images table
                                        $imageStmt->execute([$new_product_id, $fileName, $i + 1]);
                                    } else {
                                        // If the image is already smaller than our target width, just copy it
                                        if (move_uploaded_file($additionalImages['tmp_name'][$i], $uploadFile)) {
                                            // Insert into product_images table
                                            $imageStmt->execute([$new_product_id, $fileName, $i + 1]);
                                        }
                                    }
                                } else {
                                    // Fallback to direct upload without compression
                                    if (move_uploaded_file($additionalImages['tmp_name'][$i], $uploadFile)) {
                                        // Insert into product_images table
                                        $imageStmt->execute([$new_product_id, $fileName, $i + 1]);
                                    }
                                }
                            }
                        }
                    }
                }

                // Commit transaction
                $pdo->commit();

                // Em vez de redirecionar imediatamente, definimos uma flag
                $redirect = true;
                $message = 'Produto criado com sucesso!';
                $messageClass = 'success';
            }
        } catch (PDOException $e) {
            // Rollback in case of error
            $pdo->rollBack();
            
            // Se ainda assim ocorrer erro de duplicação (caso remoto)
            if ($e->getCode() == 23000 && strpos($e->getMessage(), 'Duplicate entry') !== false && strpos($e->getMessage(), 'posts.slug') !== false) {
                $message = "Produto \"" . htmlspecialchars($title) . "\" já existe. Por favor, use um nome diferente.";
            } else {
                $message = 'Erro ao criar produto: ' . $e->getMessage();
            }
            $messageClass = 'error';
            error_log($e->getMessage());
        }
    }
}

// Se a flag de redirecionamento estiver ativa, usamos JavaScript para redirecionar
if ($redirect) {
    echo "<script>window.location.href='index.php?page=product_list';</script>";
    exit(); // Ainda mantemos o exit para interromper a execução do script
}
?>

<div class="create-post">
    <h2>Adicionar Novo Produto</h2>

    <?php if ($message): ?>
        <div class="message <?= $messageClass ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="post-form" enctype="multipart/form-data">
        <div class="form-row">
            <div class="form-group">
                <label for="title">Nome do Produto *</label>
                <input type="text" id="title" name="title" required
                    value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="original_code">Código Original</label>
                <input type="text" id="original_code" name="original_code"
                    value="<?= htmlspecialchars($_POST['original_code'] ?? '') ?>">
                <small>Código original do fabricante do veículo</small>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="manufacturer_code">Código Fabricante</label>
                <input type="text" id="manufacturer_code" name="manufacturer_code"
                    value="<?= htmlspecialchars($_POST['manufacturer_code'] ?? '') ?>">
                <small>Código do fabricante da peça</small>
            </div>

            <div class="form-group">
                <label for="category_id">Categoria</label>
                <select id="category_id" name="category_id">
                    <option value="">Selecione uma categoria</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>" <?= (isset($_POST['category_id']) && $_POST['category_id'] == $category['id']) ? 'selected' : '' ?>>
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
                    value="<?= htmlspecialchars($_POST['tags'] ?? '') ?>"
                    placeholder="Separadas por vírgulas">
                <small>Ex: freio, suspensão, motor, filtro</small>
            </div>
        </div>

        <div class="form-group">
            <label for="description">Descrição Curta *</label>
            <textarea id="description" name="description" required rows="3"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
            <small>Breve resumo do produto (aparece nas listagens)</small>
        </div>

        <div class="form-group">
            <label for="content">Descrição Completa</label>
            <textarea id="content" name="content" rows="10"><?= htmlspecialchars($_POST['content'] ?? '') ?></textarea>
            <small>Descrição detalhada do produto, especificações, compatibilidade, etc.</small>
        </div>

        <div class="form-group">
            <label for="main_picture">Imagem Principal *</label>
            <input type="file" id="main_picture" name="main_picture" accept="image/*">
            <small>Formatos suportados: JPG, PNG, GIF. Esta imagem será usada como miniatura nas listagens. Imagens serão otimizadas automaticamente.</small>
        </div>

        <div class="form-group">
            <label for="additional_images">Imagens Adicionais (Máximo 5)</label>
            <input type="file" id="additional_images" name="additional_images[]" accept="image/*" multiple>
            <small>Você pode selecionar até 5 imagens adicionais para o produto. Limite total de 6 imagens por produto (1 principal + 5 adicionais). Formatos suportados: JPG, PNG, GIF.</small>
            <div id="image-preview" class="image-preview-container"></div>
        </div>

        <div class="form-row">
            <div class="form-group checkbox-group">
                <input type="checkbox" id="featured" name="featured" value="1" <?= isset($_POST['featured']) ? 'checked' : '' ?>>
                <label for="featured">Produto em Oferta</label>
                <small>Produtos em oferta aparecem na página inicial</small>
            </div>

            <div class="form-group checkbox-group">
                <input type="checkbox" id="availability" name="availability" value="1" checked>
                <label for="availability">Produto Disponível</label>
                <small>Desmarque se o produto estiver temporariamente indisponível</small>
            </div>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select id="status" name="status">
                <option value="draft" selected>Rascunho</option>
                <option value="published" <?= (isset($_POST['status']) && $_POST['status'] == 'published') ? 'selected' : '' ?>>Publicado</option>
            </select>
            <small>Apenas produtos publicados aparecem no site</small>
        </div>

        <div class="form-actions">
            <button type="submit" class="submit-button">
                <i class="fas fa-save"></i> Adicionar Produto
            </button>
            <a href="?page=product_list" class="cancel-button">
                <i class="fas fa-times"></i> Cancelar
            </a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Image preview for additional images
    const additionalImagesInput = document.getElementById('additional_images');
    const imagePreviewContainer = document.getElementById('image-preview');
    
    if (additionalImagesInput && imagePreviewContainer) {
        additionalImagesInput.addEventListener('change', function() {
            imagePreviewContainer.innerHTML = '';
            
            // Enforce the limit of 5 additional images
            if (this.files.length > 5) {
                alert('Você pode selecionar no máximo 5 imagens adicionais.');
                // Clear the input to force user to select again with proper limit
                this.value = '';
                return;
            }
            
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
});
</script>