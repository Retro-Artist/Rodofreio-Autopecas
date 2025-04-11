/**
 * Shopping Cart Functionality
 * For Rodofreios Auto Parts
 */

document.addEventListener('DOMContentLoaded', function () {
    // DOM Elements
    const cartToggle = document.getElementById('cart-toggle');
    const cartSidebar = document.getElementById('cart-sidebar');
    const cartOverlay = document.getElementById('cart-overlay');
    const cartClose = document.getElementById('cart-close');
    const cartItems = document.getElementById('cart-items');
    const cartEmpty = document.getElementById('cart-empty');
    const cartTotalValue = document.getElementById('cart-total-value');
    const cartCountElement = document.querySelector('.cart-count');
    const cartClearButton = document.getElementById('cart-clear');
    const cartCheckoutButton = document.getElementById('cart-checkout');

    // Add to cart buttons (excluding the custom one from single.php)
    const addToCartButtons = document.querySelectorAll('.product-btn-primary');

    // Base URL for product links
    const baseUrl = window.location.origin;

    // Initialize cart from localStorage
    let cart = [];
    try {
        const savedCart = localStorage.getItem('rodofreiosCart');
        if (savedCart) {
            cart = JSON.parse(savedCart);

            // Validate cart items - remove any invalid items
            cart = cart.filter(item => {
                return item &&
                    item.id &&
                    item.name &&
                    item.image &&
                    typeof item.quantity === 'number' &&
                    item.slug; // Added slug for product links
            });

            // Save cleaned cart back to localStorage
            localStorage.setItem('rodofreiosCart', JSON.stringify(cart));
        }
    } catch (error) {
        console.error('Error loading cart from localStorage:', error);
        // Reset cart if there's an error
        cart = [];
        localStorage.setItem('rodofreiosCart', JSON.stringify(cart));
    }

    // Update cart UI based on current cart state
    updateCartUI();

    // Event Listeners
    if (cartToggle) {
        cartToggle.addEventListener('click', function (e) {
            e.preventDefault();
            openCart();
        });
    }

    if (cartClose) {
        cartClose.addEventListener('click', closeCart);
    }

    if (cartOverlay) {
        cartOverlay.addEventListener('click', closeCart);
    }

    if (cartClearButton) {
        cartClearButton.addEventListener('click', clearCart);
    }

    // Checkout button handler with improved WhatsApp message format
    if (cartCheckoutButton) {
        cartCheckoutButton.addEventListener('click', function (e) {
            e.preventDefault();
            if (cart.length > 0) {
                // Track all products in cart for WhatsApp click
                cart.forEach(item => {
                    console.log('Tracking WhatsApp click for cart item:', item.id);
                    trackEvent(item.id, 'whatsapp_click');
                });

                // Para dar tempo para registrar os eventos antes de abrir o WhatsApp
                setTimeout(() => {
                    // Create WhatsApp message with cart items
                    const cartText = cart.map(item => {
                        // Format the item quantity with proper units
                        const qtyText = `(${item.quantity} ${item.quantity === 1 ? 'Unidade' : 'Unidades'})`;
                        
                        // Get product URL
                        const productUrl = `${baseUrl}/produto/${item.slug}`;
                        
                        // Format the text with manufacturer code if available
                        let itemText = `${item.name} ${qtyText}`;
                        if (item.manufacturerCode) {
                            itemText = `(Código Fabricante: ${item.manufacturerCode}) ${itemText}`;
                        }
                        
                        // Add product URL on a new line
                        itemText += `\n${productUrl}`;
                        
                        return itemText;
                    }).join('\n- ');

                    const totalItems = getTotalItems();
                    const itemText = totalItems === 1 ? 'item' : 'items';

                    const message = `Olá, tenho interesse no${cart.length > 1 ? 's' : ''} produto${cart.length > 1 ? 's' : ''}:\n- ${cartText}\n\nTotal: ${totalItems} ${itemText}`;

                    window.open(`https://wa.me/${WHATSAPP_NUMBER}?text=${encodeURIComponent(message)}`, '_blank');
                }, 300);
            }
        });
    }

    // Add event listeners to all "Add to Cart" buttons (excluding the custom one in single.php)
    if (addToCartButtons && addToCartButtons.length > 0) {
        addToCartButtons.forEach(button => {
            // Skip if the button is the custom one from single.php
            if (button.id === 'add-to-cart-custom') {
                return;
            }
            
            button.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();

                // Get product info from data attributes
                const productCard = this.closest('.product-card') || this.closest('.product-container');
                if (!productCard) return;

                const productId = productCard.dataset.productId;
                const productName = productCard.dataset.productName;
                const productImage = productCard.dataset.productImage;
                const productSlug = productCard.dataset.productSlug;
                const manufacturerCode = productCard.dataset.manufacturerCode; // Get manufacturer code if available

                // Validate required data
                if (!productId || !productName || !productImage || !productSlug) {
                    console.error('Missing required product data:', { productId, productName, productImage, productSlug });
                    showNotification('Erro ao adicionar produto. Dados incompletos.', 'error');
                    return;
                }

                // Add to cart
                addToCart({
                    id: productId,
                    name: productName,
                    image: productImage,
                    slug: productSlug,
                    quantity: 1,
                    manufacturerCode: manufacturerCode // Add manufacturer code to cart item
                });

                // Show confirmation message
                showNotification('Produto adicionado ao carrinho!');
            });
        });
    }

    // WhatsApp buttons in product cards and product page
    const whatsappButtons = document.querySelectorAll('.whatsapp-button, [href*="wa.me"]');
    if (whatsappButtons.length > 0) {
        whatsappButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                // Get product ID from closest product card or container
                const productContainer = this.closest('.product-card') || this.closest('.product-container');
                if (productContainer && productContainer.dataset.productId) {
                    trackEvent(productContainer.dataset.productId, 'whatsapp_click');
                }
            });
        });
    }

    // For single product page "Add to Cart" button
    // We're now handling this in the single.php file directly to avoid duplication
    const singleAddToCartButton = document.querySelector('.add-to-cart-button');
    if (singleAddToCartButton && singleAddToCartButton.id !== 'add-to-cart-custom') {
        singleAddToCartButton.addEventListener('click', function () {
            const productContainer = this.closest('.product-container');
            if (!productContainer) return;

            const productId = productContainer.dataset.productId;
            const productName = productContainer.dataset.productName;
            const productImage = productContainer.dataset.productImage;
            const productSlug = productContainer.dataset.productSlug;
            const manufacturerCode = productContainer.dataset.manufacturerCode; // Get manufacturer code

            // Validate required data
            if (!productId || !productName || !productImage || !productSlug) {
                console.error('Missing required product data:', { productId, productName, productImage, productSlug });
                showNotification('Erro ao adicionar produto. Dados incompletos.', 'error');
                return;
            }

            // Get quantity from input if available
            let quantity = 1;
            const quantityInput = document.getElementById('product-quantity');
            if (quantityInput) {
                quantity = parseInt(quantityInput.value) || 1;
            }

            addToCart({
                id: productId,
                name: productName,
                image: productImage,
                slug: productSlug,
                quantity: quantity,
                manufacturerCode: manufacturerCode // Include manufacturer code in cart item
            });

            showNotification('Produto adicionado ao carrinho!');
        });
    }

    /**
     * Rastreia eventos de interação com produtos
     */
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

    /**
     * Add item to cart
     */
    function addToCart(product) {
        // Validate product
        if (!product || !product.id || !product.name || !product.image || !product.slug) {
            console.error('Invalid product data:', product);
            return;
        }

        // Track the cart_add event
        trackEvent(product.id, 'cart_add');

        // Check if product already exists in cart
        const existingProductIndex = cart.findIndex(item => item.id === product.id);

        if (existingProductIndex !== -1) {
            // Increment quantity if product already in cart
            cart[existingProductIndex].quantity += product.quantity;
            // Make sure to preserve the manufacturer code
            if (product.manufacturerCode && !cart[existingProductIndex].manufacturerCode) {
                cart[existingProductIndex].manufacturerCode = product.manufacturerCode;
            }
        } else {
            // Add new product to cart
            cart.push(product);
        }

        // Update localStorage
        try {
            localStorage.setItem('rodofreiosCart', JSON.stringify(cart));
        } catch (error) {
            console.error('Error saving cart to localStorage:', error);
        }

        // Update cart UI
        updateCartUI();
    }

    /**
     * Remove item from cart
     */
    function removeFromCart(productId) {
        cart = cart.filter(item => item.id !== productId);
        localStorage.setItem('rodofreiosCart', JSON.stringify(cart));
        updateCartUI();
    }

    /**
     * Update item quantity
     */
    function updateQuantity(productId, newQuantity) {
        const index = cart.findIndex(item => item.id === productId);

        if (index !== -1 && newQuantity > 0) {
            cart[index].quantity = newQuantity;
            localStorage.setItem('rodofreiosCart', JSON.stringify(cart));
            updateCartUI();
        } else if (index !== -1 && newQuantity <= 0) {
            removeFromCart(productId);
        }
    }

    /**
     * Clear entire cart
     */
    function clearCart() {
        cart = [];
        localStorage.setItem('rodofreiosCart', JSON.stringify(cart));
        updateCartUI();
        showNotification('Carrinho limpo com sucesso!');
    }

    /**
     * Get total items in cart, counting quantities
     */
    function getTotalItems() {
        return cart.reduce((total, item) => {
            return total + (item.quantity || 0);
        }, 0);
    }

    /**
     * Update the cart UI with current cart items
     */
    function updateCartUI() {
        if (!cartItems || !cartEmpty) {
            console.error('Cart UI elements not found');
            return;
        }

        // Update cart count badge
        if (cartCountElement) {
            const totalItems = getTotalItems();
            cartCountElement.textContent = totalItems;

            // Show/hide counter based on number of items
            if (totalItems > 0) {
                cartCountElement.style.display = 'inline-flex';
            } else {
                cartCountElement.style.display = 'none';
            }
        }

        // Update total - now showing number of items instead of price
        if (cartTotalValue) {
            const totalItems = getTotalItems();
            // Fixed: Changed 'itemns' to 'items' for plural case
            cartTotalValue.textContent = `${totalItems} item${totalItems !== 1 ? 's' : ''}`;
        }

        // Update cart items
        cartItems.innerHTML = '';

        if (cart.length === 0) {
            cartEmpty.style.display = 'flex';
            cartItems.style.display = 'none';
        } else {
            cartEmpty.style.display = 'none';
            cartItems.style.display = 'block';

            cart.forEach(item => {
                try {
                    // Validate item data before rendering
                    if (!item || !item.id || !item.name || !item.image || !item.slug) {
                        console.warn('Skipping invalid cart item:', item);
                        return;
                    }

                    const cartItemElement = document.createElement('div');
                    cartItemElement.className = 'cart-item';

                    // Construct product link
                    const productUrl = `${baseUrl}/produto/${item.slug}`;

                    // New structure with details link and manufacturer code if available
                    cartItemElement.innerHTML = `
                        <div class="cart-item__image">
                            <img src="${item.image}" alt="${item.name}">
                        </div>
                        <div class="cart-item__details">
                            <div class="cart-item__info">
                                <h4 class="cart-item__name">${item.name}</h4>
                                ${item.manufacturerCode ? `<small class="cart-item__code">Código: ${item.manufacturerCode}</small>` : ''}
                                <a href="${productUrl}" class="cart-item__view-details" onclick="closeCart()">
                                    <i class="fas fa-eye"></i> Ver detalhes do produto
                                </a>
                            </div>
                            <div class="cart-item__quantity">
                                <button class="cart-item__quantity-button decrease" data-id="${item.id}">-</button>
                                <span class="cart-item__quantity-value">${item.quantity}</span>
                                <button class="cart-item__quantity-button increase" data-id="${item.id}">+</button>
                            </div>
                        </div>
                        <button class="cart-item__remove" data-id="${item.id}">
                            <i class="fas fa-times"></i>
                        </button>
                    `;

                    cartItems.appendChild(cartItemElement);

                    // Add event listeners to quantity buttons and remove button
                    const decreaseButton = cartItemElement.querySelector('.decrease');
                    const increaseButton = cartItemElement.querySelector('.increase');
                    const removeButton = cartItemElement.querySelector('.cart-item__remove');
                    const viewDetailsLink = cartItemElement.querySelector('.cart-item__view-details');

                    if (decreaseButton) {
                        decreaseButton.addEventListener('click', function () {
                            const id = this.dataset.id;
                            const currentItem = cart.find(item => item.id === id);
                            if (currentItem) {
                                updateQuantity(id, currentItem.quantity - 1);
                            }
                        });
                    }

                    if (increaseButton) {
                        increaseButton.addEventListener('click', function () {
                            const id = this.dataset.id;
                            const currentItem = cart.find(item => item.id === id);
                            if (currentItem) {
                                updateQuantity(id, currentItem.quantity + 1);
                            }
                        });
                    }

                    if (removeButton) {
                        removeButton.addEventListener('click', function () {
                            const id = this.dataset.id;
                            removeFromCart(id);
                        });
                    }

                    if (viewDetailsLink) {
                        viewDetailsLink.addEventListener('click', function (e) {
                            // Close the cart sidebar before navigating to product details
                            closeCart();
                        });
                    }
                } catch (error) {
                    console.error('Error rendering cart item:', error, item);
                }
            });
        }
    }

    /**
     * Open cart sidebar
     */
    function openCart() {
        if (!cartSidebar || !cartOverlay) return;

        cartSidebar.classList.add('active');
        cartOverlay.classList.add('active');
        document.body.style.overflow = 'hidden'; // Prevent scrolling when cart is open
    }

    /**
     * Close cart sidebar
     */
    function closeCart() {
        if (!cartSidebar || !cartOverlay) return;

        cartSidebar.classList.remove('active');
        cartOverlay.classList.remove('active');
        document.body.style.overflow = ''; // Re-enable scrolling
    }

    /**
     * Show notification
     * @param {string} message - The message to display
     * @param {string} type - The type of notification ('success' or 'error')
     */
    function showNotification(message, type = 'success') {
        // Remove any existing notifications
        const existingNotification = document.querySelector('.cart-notification');
        if (existingNotification) {
            document.body.removeChild(existingNotification);
        }

        const notification = document.createElement('div');
        notification.className = 'cart-notification';

        const iconClass = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        const backgroundColor = type === 'success' ? '#4caf50' : '#f44336';

        notification.innerHTML = `
            <div class="cart-notification__content" style="background-color: ${backgroundColor}">
                <i class="fas ${iconClass}"></i>
                <p>${message}</p>
            </div>
        `;

        document.body.appendChild(notification);

        // Trigger animation
        setTimeout(() => {
            notification.classList.add('active');
        }, 10);

        // Remove notification after 3 seconds
        setTimeout(() => {
            notification.classList.remove('active');
            setTimeout(() => {
                if (document.body.contains(notification)) {
                    document.body.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }

    // Make necessary functions available globally for use in product pages
    window.closeCart = closeCart;
    window.addToCart = addToCart;
    window.showNotification = showNotification;
    window.trackEvent = trackEvent;
});