<?php
// sections/single.php
// Displays a single product with details for Rodofreios

// Get the slug from the URL
$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';

if (empty($slug)) {
    header('Location: ' . BASE_URL);
    exit;
}

// Get the product (using posts table)
try {
    $query = "SELECT p.*, c.name as category_name, c.slug as category_slug
              FROM posts p
              LEFT JOIN categories c ON p.category_id = c.id
              LEFT JOIN users u ON p.user_id = u.id
              WHERE p.slug = :slug 
              AND p.status = 'published'";

    $stmt = $pdo->prepare($query);
    $stmt->execute(['slug' => $slug]);

    $product = $stmt->fetch();

    if (!$product) {
        // Product not found
        include __DIR__ . '/404.php';
        exit;
    }

    $availability = isset($product['availability']) ? $product['availability'] == 1 : true;
} catch (PDOException $e) {
    error_log($e->getMessage());
    include __DIR__ . '/404.php';
    exit;
}

// Format dates
$published_date = new DateTime($product['created_at']);
$formatted_date = $published_date->format('d/m/Y');

// Get related products (products in the same category)
$related_products = [];
if (!empty($product['category_id'])) {
    try {
        $related_query = "SELECT p.id, p.title, p.slug, p.main_picture, p.description, 
                          p.availability, p.featured, p.original_code, p.manufacturer_code
                         FROM posts p
                         WHERE p.id != :product_id 
                         AND p.category_id = :category_id
                         AND p.status = 'published'
                         ORDER BY p.created_at DESC
                         LIMIT 4";

        $related_stmt = $pdo->prepare($related_query);
        $related_stmt->execute([
            'product_id' => $product['id'],
            'category_id' => $product['category_id']
        ]);
        $related_products = $related_stmt->fetchAll();
    } catch (PDOException $e) {
        error_log($e->getMessage());
        // Continue execution even if related products fail
    }
}
?>

<div class="product-page">
    <div class="page-wrapper">
        <main class="main-content">
            <!-- Breadcrumb navigation -->
            <div class="breadcrumb">
                <div class="container">
                    <ul class="breadcrumb-list">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/">Home</a></li>
                        <?php if (!empty($product['category_name'])): ?>
                            <li class="breadcrumb-item">
                                <a href="<?= BASE_URL ?>/produtos/categoria/<?= urlencode($product['category_slug']) ?>">
                                    <?= htmlspecialchars($product['category_name']) ?>
                                </a>
                            </li>
                        <?php endif; ?>
                        <li class="breadcrumb-item active"><?= htmlspecialchars($product['title']) ?></li>
                    </ul>
                </div>
            </div>

            <!-- In sections/single.php, around line 37 in the product container -->
            <div class="product-container"
                data-product-id="<?= $product['id'] ?>"
                data-product-name="<?= htmlspecialchars($product['title']) ?>"
                data-product-image="<?= !empty($product['main_picture']) ? UPLOADS_URL . $product['main_picture'] : IMAGES_URL . 'placeholder.webp' ?>"
                data-product-slug="<?= $product['slug'] ?>">

                <div class="product-gallery">
                    <div class="product-main-image">
                        <?php if (!empty($product['main_picture'])): ?>
                            <img src="<?= UPLOADS_URL . $product['main_picture'] ?>"
                                alt="<?= htmlspecialchars($product['title']) ?>" id="main-product-image">
                        <?php else: ?>
                            <img src="<?= IMAGES_URL ?>placeholder.webp"
                                alt="<?= htmlspecialchars($product['title']) ?>" id="main-product-image">
                        <?php endif; ?>
                    </div>
                    <!-- Removed thumbnails section -->
                </div>

                <div class="product-details">
                    <h1 class="product-title"><?= htmlspecialchars($product['title']) ?></h1>

                    <div class="product-meta">
                        <?php if (!empty($product['category_name'])): ?>
                            <div class="product-category">
                                Categoria:
                                <a href="<?= BASE_URL ?>/produtos/categoria/<?= urlencode($product['category_slug']) ?>" class="category-link">
                                    <?= htmlspecialchars($product['category_name']) ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($product['tags'])): ?>
                            <div class="product-tags">
                                Tags:
                                <?php
                                $tags = explode(',', $product['tags']);
                                foreach ($tags as $index => $tag):
                                    $tag = trim($tag);
                                    if (!empty($tag)):
                                        echo ($index > 0 ? ', ' : '') .
                                            '<span class="tag">' . htmlspecialchars($tag) . '</span>';
                                    endif;
                                endforeach;
                                ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Display product codes -->
                    <div class="product-codes">
                        <?php if (!empty($product['original_code'])): ?>
                            <div class="product-code">
                                <span class="code-label">COD:</span>
                                <span class="code-value"><?= htmlspecialchars($product['original_code']) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="product-short-description">
                        <?= htmlspecialchars($product['description']) ?>
                    </div>

                    <div class="product-availability">
                        <?php if ($availability): ?>
                            <span class="in-stock">
                                <i class="fas fa-check-circle"></i> Em disponibilidade
                            </span>
                        <?php else: ?>
                            <span class="out-of-stock">
                                <i class="fas fa-times-circle"></i> Em falta
                            </span>
                        <?php endif; ?>

                        <div class="availability-note">
                            Consulte valores e disponibilidade pelo WhatsApp.
                        </div>
                    </div>

                    <!-- In sections/single.php, update the product actions -->
                    <div class="product-actions">
                        <button class="add-to-cart-button">
                            <i class="fas fa-shopping-cart"></i> Adicionar ao Carrinho
                        </button>

                        <a href="<?= getWhatsAppUrl(WHATSAPP_NUMBER, 'Olá, tenho interesse no produto: ' . $product['title'] . 
                            (!empty($product['original_code']) ? ' (Código Original: ' . $product['original_code'] . ')' : '') .
                            (!empty($product['manufacturer_code']) ? ' (Código Fabricante: ' . $product['manufacturer_code'] . ')' : '')) ?>"
                            class="whatsapp-button" target="_blank">
                            <i class="fab fa-whatsapp"></i> Compre agora pelo WhatsApp
                        </a>
                    </div>
                </div>
            </div>

            <!-- Product description section (SEPARADO COMO SOLICITADO) -->
            <?php if (!empty($product['content'])): ?>
                <div class="product-description-section">
                    <h2>Descrição do Produto</h2>
                    <div class="product-description">
                        <?= nl2br(htmlspecialchars($product['content'])) ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Related products -->
            <?php if (!empty($related_products)): ?>
                <div class="related-products">
                    <h2>Produtos Relacionados</h2>
                    <div class="products-grid">
                        <?php foreach ($related_products as $related):
                            $related_url = BASE_URL . '/produto/' . $related['slug'];
                        ?>
                            <div class="product-card"
                                data-product-id="<?= $related['id'] ?>"
                                data-product-name="<?= htmlspecialchars($related['title']) ?>"
                                data-product-image="<?= !empty($related['main_picture']) ? UPLOADS_URL . $related['main_picture'] : IMAGES_URL . 'placeholder.webp' ?>"
                                data-product-slug="<?= $related['slug'] ?>">
                                <!-- Full card clickable link -->
                                <a href="<?= $related_url ?>" class="product-card-link" aria-label="Ver detalhes de <?= htmlspecialchars($related['title']) ?>"></a>

                                <?php if (!empty($related['featured'])): ?>
                                    <div class="product-badge">Em Oferta</div>
                                <?php endif; ?>

                                <div class="product-image">
                                    <?php if (!empty($related['main_picture'])): ?>
                                        <img src="<?= UPLOADS_URL . $related['main_picture'] ?>" alt="<?= htmlspecialchars($related['title']) ?>">
                                    <?php else: ?>
                                        <img src="<?= IMAGES_URL ?>placeholder.webp" alt="<?= htmlspecialchars($related['title']) ?>">
                                    <?php endif; ?>
                                </div>

                                <div class="product-info">
                                    <h3 class="product-title"><?= htmlspecialchars($related['title']) ?></h3>
                                    
                                    <!-- Display codes for related products -->
                                    <?php if (!empty($related['original_code']) || !empty($related['manufacturer_code'])): ?>
                                    <div class="product-codes-mini">
                                        <?php if (!empty($related['original_code'])): ?>
                                            <div class="product-code-mini">
                                                <span class="code-label-mini">Código Original:</span>
                                                <span class="code-value-mini"><?= htmlspecialchars($related['original_code']) ?></span>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($related['manufacturer_code'])): ?>
                                            <div class="product-code-mini">
                                                <span class="code-label-mini">Código Fabricante:</span>
                                                <span class="code-value-mini"><?= htmlspecialchars($related['manufacturer_code']) ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <div class="product-contact">
                                        <i class="fab fa-whatsapp"></i>
                                        <span>Compra pelo WhatsApp</span>
                                    </div>
                                </div>

                                <div class="product-action-buttons">
                                    <a href="<?= $related_url ?>" class="product-btn-secondary" onclick="event.stopPropagation();">
                                        <i class="fas fa-eye"></i> Ver Detalhes
                                    </a>
                                    <a href="<?= getWhatsAppUrl(WHATSAPP_NUMBER, 'Olá, tenho interesse no produto: ' . $related['title'] . 
                                        (!empty($related['original_code']) ? ' (Código Original: ' . $related['original_code'] . ')' : '') .
                                        (!empty($related['manufacturer_code']) ? ' (Código Fabricante: ' . $related['manufacturer_code'] . ')' : '')) ?>" 
                                        target="_blank" class="product-btn-primary" onclick="event.stopPropagation();">
                                        <i class="fas fa-shopping-cart"></i> Adicionar
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>

<style>
/* Product codes styling */
.product-codes {
    margin: 15px 0;
    padding: 12px;
    background-color: #f8f9fa;
    border-radius: 5px;
    border: 1px solid #e9ecef;
}

.product-code {
    margin-bottom: 8px;
}

.product-code:last-child {
    margin-bottom: 0;
}

.code-label {
    font-weight: 600;
    display: inline-block;
    width: 140px;
}

.code-value {
    font-family: monospace;
    background-color: #fff;
    padding: 2px 8px;
    border-radius: 3px;
    border: 1px solid #e9ecef;
}

/* Related product code styling */
.product-codes-mini {
    margin: 8px 0;
    font-size: 0.85rem;
}

.product-code-mini {
    margin-bottom: 4px;
}

.code-label-mini {
    font-weight: 500;
    color: #666;
}

.code-value-mini {
    font-family: monospace;
    background-color: #f8f9fa;
    padding: 1px 4px;
    border-radius: 2px;
    font-size: 0.8rem;
}
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Track product view
        const productContainer = document.querySelector('.product-container');
        if (productContainer && productContainer.dataset.productId) {
            // Função para rastrear eventos
            function trackEvent(productId, eventType) {
                fetch(window.location.origin + '/includes/track.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            event_type: eventType
                        })
                    })
                    .catch(error => {
                        console.error('Error tracking event:', error);
                    });
            }

            // Rastrear visualização do produto
            trackEvent(productContainer.dataset.productId, 'view');

            // Adicionar rastreamento ao botão do WhatsApp
            const whatsappButton = document.querySelector('.whatsapp-button');
            if (whatsappButton) {
                whatsappButton.addEventListener('click', function() {
                    trackEvent(productContainer.dataset.productId, 'whatsapp_click');
                    console.log('WhatsApp click tracked for product:', productContainer.dataset.productId);
                });
            }
        }
    });
</script>