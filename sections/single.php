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

    // Get additional images for the product
    $additional_images = [];
    try {
        $images_query = "SELECT * FROM product_images 
                         WHERE product_id = :product_id 
                         ORDER BY display_order ASC
                         LIMIT 5"; // Limit to 5 additional images (6 total with main image)
        $images_stmt = $pdo->prepare($images_query);
        $images_stmt->execute(['product_id' => $product['id']]);
        $additional_images = $images_stmt->fetchAll();
    } catch (PDOException $e) {
        error_log($e->getMessage());
        // Continue execution even if additional images fail to load
    }

    // Log the results for debugging
    error_log('Product ID: ' . $product['id'] . ', Additional Images: ' . count($additional_images));
    error_log('Main Image: ' . ($product['main_picture'] ? $product['main_picture'] : 'None'));
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

// Track the product view
if (!empty($product['id'])) {
    try {
        $track_data = [
            'product_id' => $product['id'],
            'event_type' => 'view',
            'user_ip' => $_SERVER['REMOTE_ADDR'] ?? null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
            'referrer' => $_SERVER['HTTP_REFERER'] ?? null
        ];

        $track_query = "INSERT INTO product_events 
                       (product_id, event_type, user_ip, user_agent, referrer) 
                       VALUES (:product_id, :event_type, :user_ip, :user_agent, :referrer)";
        $track_stmt = $pdo->prepare($track_query);
        $track_stmt->execute($track_data);
    } catch (PDOException $e) {
        error_log('Error tracking product view: ' . $e->getMessage());
        // Continue execution even if tracking fails
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

            <!-- Product container with updated design -->
            <div class="product-container"
                data-product-id="<?= $product['id'] ?>"
                data-product-name="<?= htmlspecialchars($product['title']) ?>"
                data-product-image="<?= !empty($product['main_picture']) ? UPLOADS_URL . $product['main_picture'] : IMAGES_URL . 'placeholder.webp' ?>"
                data-product-slug="<?= $product['slug'] ?>"
                data-manufacturer-code="<?= htmlspecialchars($product['manufacturer_code'] ?? '') ?>">

                <div class="product-gallery">
                    <!-- Main product image - Increased size -->
                    <div class="product-main-image">
                        <?php if (!empty($product['main_picture'])): ?>
                            <img src="<?= UPLOADS_URL . $product['main_picture'] ?>"
                                alt="<?= htmlspecialchars($product['title']) ?>" id="main-product-image">
                        <?php else: ?>
                            <img src="<?= IMAGES_URL ?>placeholder.webp"
                                alt="<?= htmlspecialchars($product['title']) ?>" id="main-product-image">
                        <?php endif; ?>
                    </div>

                    <!-- Thumbnails gallery - Limited to 6 total (main + 5 additional) -->
                    <?php
                    $has_thumbnails = (!empty($product['main_picture']) || !empty($additional_images));
                    if ($has_thumbnails):
                    ?>
                        <div class="product-thumbnails">
                            <!-- Main image as first thumbnail -->
                            <?php if (!empty($product['main_picture'])): ?>
                                <div class="thumbnail active" data-src="<?= UPLOADS_URL . $product['main_picture'] ?>">
                                    <img src="<?= UPLOADS_URL . $product['main_picture'] ?>"
                                        alt="<?= htmlspecialchars($product['title']) ?> - Imagem principal">
                                </div>
                            <?php endif; ?>

                            <!-- Additional images as thumbnails - Already limited to 5 by the SQL query -->
                            <?php foreach ($additional_images as $index => $image): ?>
                                <div class="thumbnail" data-src="<?= UPLOADS_URL . $image['image_path'] ?>">
                                    <img src="<?= UPLOADS_URL . $image['image_path'] ?>"
                                        alt="<?= htmlspecialchars($product['title']) ?> - Imagem <?= $index + 1 ?>">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="product-details">
                    <h1 class="product-title"><?= htmlspecialchars($product['title']) ?></h1>

                    <!-- Availability status with improved visual -->
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
                    </div>

                    <!-- Product description with better emphasis -->
                    <div class="product-description">
                        <p><?= htmlspecialchars($product['description']) ?></p>
                    </div>

                    <!-- Quantity selector -->
                    <div class="product-quantity">
                        <label for="product-quantity">Quantidade:</label>
                        <div class="quantity-selector">
                            <button type="button" class="quantity-btn decrease" id="decrease-quantity">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number" id="product-quantity" class="quantity-input" value="1" min="1" max="99">
                            <button type="button" class="quantity-btn increase" id="increase-quantity">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Category and tags in a more compact format -->
                    <div class="product-meta">
                        <?php if (!empty($product['category_name'])): ?>
                            <div class="product-category">
                                <a href="<?= BASE_URL ?>/produtos/categoria/<?= urlencode($product['category_slug']) ?>" class="category-link">
                                    <i class="fas fa-tag"></i> <?= htmlspecialchars($product['category_name']) ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($product['tags'])): ?>
                            <div class="product-tags">
                                <?php
                                $tags = explode(',', $product['tags']);
                                foreach ($tags as $index => $tag):
                                    $tag = trim($tag);
                                    if (!empty($tag)):
                                        echo '<span class="tag">' . htmlspecialchars($tag) . '</span>';
                                    endif;
                                endforeach;
                                ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Manufacturer code displayed in a minimal badge -->
                    <?php if (!empty($product['manufacturer_code'])): ?>
                        <div class="product-code-badge">
                            <span class="code-value">Código de Fabricate: <?= htmlspecialchars($product['manufacturer_code']) ?></span>
                        </div>
                    <?php endif; ?>

                    <!-- Call-to-action note -->
                    <div class="product-cta-note">
                        <i class="fas fa-info-circle"></i> Consulte valores e disponibilidade pelo WhatsApp.
                    </div>

                    <!-- Product actions with improved styling -->
                    <div class="product-actions">
                        <button class="add-to-cart-button" id="add-to-cart-custom">
                            <i class="fas fa-shopping-cart"></i> Adicionar ao Carrinho
                        </button>

                        <?php
                        // Prepare WhatsApp message with new template
                        $manufacturerCodePrefix = !empty($product['manufacturer_code']) ? "*{$product['manufacturer_code']}* - " : "";
                        $productName = htmlspecialchars($product['title']);
                        $productUrl = BASE_URL . '/produto/' . $product['slug'];
                        $whatsappMsg = "Olá, gostaria de finalizar a compra dos seguintes produtos:\n\n- {$manufacturerCodePrefix}{$productName} (1x)\n\nTotal: 1 item";
                        ?>

                        <a href="<?= getWhatsAppUrl(WHATSAPP_NUMBER, $whatsappMsg) ?>"
                            class="whatsapp-button" target="_blank" id="whatsapp-btn">
                            <i class="fab fa-whatsapp"></i> Compre agora pelo WhatsApp
                        </a>
                    </div>
                </div>
            </div>

            <!-- Product description section with improved styling -->
            <?php if (!empty($product['content'])): ?>
                <div class="product-description-section">
                    <h2>Descrição do Produto</h2>
                    <div class="product-full-description">
                        <?= nl2br(htmlspecialchars($product['content'])) ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Related products section with improved styling -->
            <?php if (!empty($related_products)): ?>
                <div class="related-products">
                    <h2 class="related-products-title">Produtos Relacionados</h2>
                    <div class="products-grid">
                        <?php foreach ($related_products as $related):
                            $related_url = BASE_URL . '/produto/' . $related['slug'];
                        ?>
                            <div class="product-card"
                                data-product-id="<?= $related['id'] ?>"
                                data-product-name="<?= htmlspecialchars($related['title']) ?>"
                                data-product-image="<?= !empty($related['main_picture']) ? UPLOADS_URL . $related['main_picture'] : IMAGES_URL . 'placeholder.webp' ?>"
                                data-product-slug="<?= $related['slug'] ?>"
                                data-manufacturer-code="<?= htmlspecialchars($related['manufacturer_code'] ?? '') ?>">

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

                                    <!-- Display manufacturer code in minimal format for related products -->
                                    <?php if (!empty($related['manufacturer_code'])): ?>
                                        <div class="product-code-mini">
                                            Código: <?= htmlspecialchars($related['manufacturer_code']) ?>
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
                                    <a href="#" class="product-btn-primary" onclick="event.preventDefault(); event.stopPropagation();">
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Image gallery functionality
        const mainImage = document.getElementById('main-product-image');
        const thumbnails = document.querySelectorAll('.thumbnail');

        // Set up thumbnail click handlers
        if (thumbnails.length > 0 && mainImage) {
            thumbnails.forEach(thumbnail => {
                thumbnail.addEventListener('click', function() {
                    // Update main image
                    const imgSrc = this.getAttribute('data-src');
                    if (imgSrc) {
                        mainImage.src = imgSrc;

                        // Update active state
                        thumbnails.forEach(thumb => thumb.classList.remove('active'));
                        this.classList.add('active');
                    }
                });
            });
        }

        // Quantity selector functionality
        const quantityInput = document.getElementById('product-quantity');
        const decreaseBtn = document.getElementById('decrease-quantity');
        const increaseBtn = document.getElementById('increase-quantity');

        if (quantityInput && decreaseBtn && increaseBtn) {
            decreaseBtn.addEventListener('click', function() {
                const currentValue = parseInt(quantityInput.value);
                if (currentValue > 1) {
                    quantityInput.value = currentValue - 1;
                    updateWhatsAppLink();
                }
            });

            increaseBtn.addEventListener('click', function() {
                const currentValue = parseInt(quantityInput.value);
                if (currentValue < 99) {
                    quantityInput.value = currentValue + 1;
                    updateWhatsAppLink();
                }
            });

            quantityInput.addEventListener('change', function() {
                let value = parseInt(this.value);
                if (isNaN(value) || value < 1) {
                    value = 1;
                } else if (value > 99) {
                    value = 99;
                }
                this.value = value;
                updateWhatsAppLink();
            });

            // Update WhatsApp link with current quantity using the new message format
            function updateWhatsAppLink() {
                const quantity = parseInt(quantityInput.value);
                const whatsappBtn = document.getElementById('whatsapp-btn');

                if (whatsappBtn && quantity > 0) {
                    const productContainer = document.querySelector('.product-container');
                    if (!productContainer) return;

                    const productId = productContainer.dataset.productId;
                    const productName = productContainer.dataset.productName;
                    const productSlug = productContainer.dataset.productSlug;
                    const manufacturerCode = productContainer.dataset.manufacturerCode || '';

                    const baseUrl = window.location.origin;
                    const productUrl = `${baseUrl}/produto/${productSlug}`;

                    // Format with the new template
                    let itemText = '';
                    if (manufacturerCode) {
                        itemText = `*${manufacturerCode}* - ${productName} (${quantity}x)`;
                    } else {
                        itemText = `${productName} (${quantity}x)`;
                    }

                    const itemWord = quantity === 1 ? 'item' : 'itens';

                    // Create the WhatsApp message using the new format
                    let messageText = `Olá, gostaria de finalizar a compra dos seguintes produtos:\n\n- ${itemText}\n\nTotal: ${quantity} ${itemWord}`;

                    // Update the href attribute
                    whatsappBtn.href = `https://wa.me/${WHATSAPP_NUMBER}?text=${encodeURIComponent(messageText)}`;
                }
            }

            // Custom Add to Cart button functionality with quantity
            const addToCartBtn = document.getElementById('add-to-cart-custom');
            if (addToCartBtn) {
                addToCartBtn.addEventListener('click', function() {
                    const quantity = parseInt(quantityInput.value);
                    const productContainer = document.querySelector('.product-container');
                    if (!quantity > 0 || !productContainer) return;

                    const productId = productContainer.dataset.productId;
                    const productName = productContainer.dataset.productName;
                    const productImage = productContainer.dataset.productImage;
                    const productSlug = productContainer.dataset.productSlug;
                    const manufacturerCode = productContainer.dataset.manufacturerCode || '';

                    // Call the global addToCart function from cart.js
                    if (typeof window.addToCart === 'function') {
                        window.addToCart({
                            id: productId,
                            name: productName,
                            image: productImage,
                            slug: productSlug,
                            quantity: quantity,
                            manufacturerCode: manufacturerCode
                        });

                        // Show notification using the global function
                        if (typeof window.showNotification === 'function') {
                            window.showNotification(`${quantity} ${quantity === 1 ? 'unidade' : 'unidades'} adicionada${quantity === 1 ? '' : 's'} ao carrinho!`);
                        }
                    } else {
                        // Fallback if addToCart is not available globally
                        console.log('Added to cart:', {
                            id: productId,
                            name: productName,
                            quantity: quantity,
                            manufacturerCode: manufacturerCode
                        });
                        alert(`${quantity} ${quantity === 1 ? 'unidade' : 'unidades'} adicionada${quantity === 1 ? '' : 's'} ao carrinho!`);
                    }
                });
            }
        }

        // WhatsApp button tracking
        const whatsappBtn = document.getElementById('whatsapp-btn');
        if (whatsappBtn) {
            whatsappBtn.addEventListener('click', function() {
                const productContainer = document.querySelector('.product-container');
                if (productContainer && productContainer.dataset.productId) {
                    // Track WhatsApp click event
                    trackEvent(productContainer.dataset.productId, 'whatsapp_click');
                }
            });
        }

        // Track event function - direct implementation for when window.trackEvent is not available
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
    });
</script>