<?php
// sections/home.php
// Homepage for Rodofreios Auto Parts

// Query to fetch featured and recent products
$featured_query = "SELECT p.id, p.title, p.slug, p.main_picture, p.description, 
                         p.featured, p.category_id, p.availability, 
                         c.name as category_name, c.slug as category_slug
                   FROM posts p
                   LEFT JOIN categories c ON p.category_id = c.id 
                   WHERE p.status = 'published'
                   ORDER BY p.featured DESC, p.created_at DESC 
                   LIMIT 8";

try {
    $stmt = $pdo->prepare($featured_query);
    $stmt->execute();
    $featured_products = $stmt->fetchAll();
} catch (PDOException $e) {
    // Log error silently
    error_log("Error fetching featured products: " . $e->getMessage());
    $featured_products = [];
}
?>

<main class="home-page">
    <!----------------------Hero Banner Section---------------------->
    <section class="hero-banner">
        <div class="hero-overlay"></div>
        <div class="whatsapp-floating">
            <a href="<?= getWhatsAppUrl(WHATSAPP_NUMBER) ?>" target="_blank">
                <i class="fab fa-whatsapp"></i>
            </a>
        </div>
    </section>

    <div class="home-page__wrapper">
        <div class="home-page__content">

            <!----------------------Benefits Section---------------------->
            <section class="benefits-section">
                <div class="benefits-container">
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <img src="<?= IMAGES_URL ?>icone-beneficio-01.png" alt="Ícone caminhão">
                        </div>
                        <div class="benefit-content">
                            <h3 class="benefit-content__title">COMPRE NO SITE</h3>
                            <p class="benefit-content__description">Receba em casa</p>
                        </div>
                    </div>

                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <img src="<?= IMAGES_URL ?>icone-beneficio-02.png" alt="Ícone escudo">
                        </div>
                        <div class="benefit-content">
                            <h3 class="benefit-content__title">LOJA 100% CONFIÁVEL</h3>
                            <p class="benefit-content__description">Compre com tranquilidade</p>
                        </div>
                    </div>

                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <img src="<?= IMAGES_URL ?>icone-beneficio-03.png" alt="Ícone conversa">
                        </div>
                        <div class="benefit-content">
                            <h3 class="benefit-content__title">COMPRAS NO WHATSAPP</h3>
                            <p class="benefit-content__description">Amada pelos clientes</p>
                        </div>
                    </div>
                </div>
            </section>

            <!----------------------About Preview Section---------------------->
            <section class="about-preview">
                <div class="about-container">
                    <div class="about-image">
                        <img src="<?= IMAGES_URL ?>main-preview.webp" alt="Rodofreios Autopeças">
                    </div>
                    <div class="about-content">
                        <h2 class="about-content__title">Especializados em peças automotivas para veículos pesados e comerciais</h2>

                        <p class="about-content__text">Com mais de 32 anos de experiência no mercado, a Rodofreios se consolidou como uma referência em peças automotivas para caminhões, carretas e veículos comerciais. Nossa missão é fornecer soluções de alta qualidade que garantam segurança, desempenho e economia para sua frota.</p>

                        <p class="about-content__text">Trabalhamos com as melhores marcas do mercado, oferecendo um catálogo extenso e atualizado de peças para diferentes modelos e marcas de veículos. Nossa equipe técnica especializada está sempre pronta para auxiliar você na escolha correta da peça ideal para seu veículo.</p>

                        <a href="<?= BASE_URL ?>/produtos" class="cta-button">
                            Compre Conosco
                        </a>
                    </div>
                </div>
            </section>

            <!----------------------Featured Products Section---------------------->
            <section class="featured-products">
                <div class="section-header">
                    <h2 class="section-header__title">Produtos em Destaque</h2>
                </div>

                <div class="products-grid">
                    <?php
                    // Display only the first 4 products
                    $display_products = array_slice($featured_products, 0, 4);

                    foreach ($display_products as $product):
                        $url = BASE_URL . '/produto/' . $product['slug'];
                    ?>
                        <div class="product-card"
                            data-product-id="<?= $product['id'] ?>"
                            data-product-name="<?= htmlspecialchars($product['title']) ?>"
                            data-product-image="<?= !empty($product['main_picture']) ? UPLOADS_URL . $product['main_picture'] : IMAGES_URL . 'placeholder.webp' ?>"
                            data-product-slug="<?= $product['slug'] ?>">

                            <!-- Full card clickable link -->
                            <a href="<?= $url ?>" class="product-card-link" aria-label="Ver detalhes de <?= htmlspecialchars($product['title']) ?>"></a>

                            <?php if (!empty($product['featured'])): ?>
                                <div class="product-badge">Em Oferta</div>
                            <?php endif; ?>

                            <div class="product-image">
                                <?php if (!empty($product['main_picture'])): ?>
                                    <img src="<?= UPLOADS_URL . $product['main_picture'] ?>" alt="<?= htmlspecialchars($product['title']) ?>">
                                <?php else: ?>
                                    <img src="<?= IMAGES_URL ?>placeholder.webp" alt="<?= htmlspecialchars($product['title']) ?>">
                                <?php endif; ?>
                            </div>

                            <div class="product-info">
                                <h3 class="product-title"><?= htmlspecialchars($product['title']) ?></h3>
                                <div class="product-contact">
                                    <i class="fab fa-whatsapp"></i>
                                    <span>Compra pelo WhatsApp</span>
                                </div>
                            </div>

                            <!-- In sections/home.php, update the product action buttons -->
                            <div class="product-action-buttons">
                                <a href="<?= $url ?>" class="product-btn-secondary" onclick="event.stopPropagation();">
                                    <i class="fas fa-eye"></i> Ver Detalhes
                                </a>
                                <a href="#" class="product-btn-primary" onclick="event.preventDefault(); event.stopPropagation();">
                                    <i class="fas fa-shopping-cart"></i> Adicionar ao Carrinho
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="view-all-container">
                    <a href="<?= BASE_URL ?>/produtos" class="view-all-button">Ver Todos os Produtos</a>
                </div>
            </section>

            <!----------------------Brands/Partners Section---------------------->
            <section class="brands-section">
                <div class="section-header">
                    <h2 class="section-header__title">Marcas Parceiras</h2>
                </div>

                <div class="brands-slider">
                    <div class="brands-track">
                        <div class="brand-item">
                            <img src="<?= IMAGES_URL ?>marca-01.png" alt="Marca Parceira">
                        </div>
                        <div class="brand-item">
                            <img src="<?= IMAGES_URL ?>marca-03.png" alt="Marca Parceira">
                        </div>
                        <div class="brand-item">
                            <img src="<?= IMAGES_URL ?>marca-04.png" alt="Marca Parceira">
                        </div>
                        <div class="brand-item">
                            <img src="<?= IMAGES_URL ?>marca-05.png" alt="Marca Parceira">
                        </div>
                        <div class="brand-item">
                            <img src="<?= IMAGES_URL ?>marca-06.png" alt="Marca Parceira">
                        </div>
                    </div>
                </div>
            </section>

            <!----------------------Experience and Benefits Section---------------------->
            <section class="experience-section">
                <div class="experience-container">
                    <div class="experience-block">
                        <div class="years-experience">
                            <h2 class="years-experience__number">32<span class="years-experience__plus">+</span></h2>
                            <h3 class="years-experience__title">ANOS DE EXPERIÊNCIA</h3>
                            <p class="years-experience__text">Desde 1992, trabalhamos com as melhores peças automotivas.</p>
                        </div>
                    </div>

                    <div class="benefits-blocks">
                        <div class="benefit-block">
                            <div class="benefit-image">
                                <img src="<?= IMAGES_URL ?>icon-stock.png" alt="Ícone de estoque">
                            </div>
                            <div class="benefit-content">
                                <h3 class="benefit-content__title">Grande Estoque à Pronta Entrega</h3>
                                <p class="benefit-content__description">Sabemos da importância de ter as peças certas disponíveis quando você precisa. Mantemos um amplo estoque de produtos à pronta entrega, assegurando rapidez e eficiência no atendimento.</p>
                            </div>
                        </div>

                        <div class="benefit-block">
                            <div class="benefit-image">
                                <img src="<?= IMAGES_URL ?>icon-service.png" alt="Ícone de atendimento">
                            </div>
                            <div class="benefit-content">
                                <h3 class="benefit-content__title">Atendimento Personalizado</h3>
                                <p class="benefit-content__description">Focamos nosso trabalho em um atendimento personalizado, proporcionando qualidade. Nossa oficina na reposição de peças automotivas com pontualidade, garantia e preços competitivos.</p>
                            </div>
                        </div>

                        <div class="benefit-block">
                            <div class="benefit-image">
                                <img src="<?= IMAGES_URL ?>icon-quality.png" alt="Ícone de qualidade">
                            </div>
                            <div class="benefit-content">
                                <h3 class="benefit-content__title">Peças de Alta Qualidade</h3>
                                <p class="benefit-content__description">Trabalhamos apenas com marcas reconhecidas pela durabilidade e confiabilidade, garantindo que seu veículo receba o que há de melhor.</p>
                            </div>
                        </div>

                        <div class="benefit-block">
                            <div class="benefit-image">
                                <img src="<?= IMAGES_URL ?>icon-specialists.png" alt="Ícone de especialistas">
                            </div>
                            <div class="benefit-content">
                                <h3 class="benefit-content__title">Mantemos um time de especialistas para melhor atendê-lo</h3>
                                <p class="benefit-content__description">Nossa equipe de especialistas está sempre à disposição para oferecer orientações técnicas e ajudar você a encontrar a peça ideal para seu veículo.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!----------------------Quality Guarantee Section---------------------->
            <section class="quality-section">
                <div class="quality-container">
                    <div class="quality-image">
                        <img src="<?= IMAGES_URL ?>inside.webp" alt="Imagem de qualidade">
                    </div>
                    <div class="quality-content">
                        <h2 class="quality-content__title">Compromisso com Excelência e Confiabilidade</h2>
                        <p class="quality-content__text">Na Rodofreios, entendemos que cada peça é fundamental para o desempenho e segurança do seu veículo. Por isso, selecionamos rigorosamente nossos fornecedores e mantemos um controle de qualidade que garante a procedência e eficiência de cada componente.</p>

                        <div class="quality-features">
                            <div class="quality-feature">
                                <div class="feature-icon">
                                    <img src="<?= IMAGES_URL ?>icon-trust.png" alt="Ícone de confiança">
                                </div>
                                <div class="feature-content">
                                    <h3 class="feature-content__title">Qualidade Certificada</h3>
                                    <p class="feature-content__text">Trabalhamos apenas com marcas homologadas e peças originais ou de primeira linha, garantindo durabilidade e performance para seu veículo comercial ou de transporte pesado.</p>
                                </div>
                            </div>

                            <div class="quality-feature">
                                <div class="feature-icon">
                                    <img src="<?= IMAGES_URL ?>icon-service.png" alt="Ícone de atendimento">
                                </div>
                                <div class="feature-content">
                                    <h3 class="feature-content__title">Suporte Técnico Especializado</h3>
                                    <p class="feature-content__text">Nossa equipe de especialistas oferece consultoria personalizada, ajudando você a encontrar a peça perfeita e garantindo a melhor solução para sua necessidade específica.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!----------------------Testimonials Section---------------------->
            <section class="testimonials-section">
                <div class="container">
                    <div class="section-header">
                        <span class="section-tag">DEPOIMENTOS</span>
                        <h2 class="section-header__title">O QUE NOSSOS CLIENTES DIZEM</h2>
                    </div>

                    <div class="testimonials-grid">
                        <div class="testimonial-card">
                            <div class="testimonial-header">
                                <div class="client-info">
                                    <div class="client-avatar">
                                        <img src="<?= IMAGES_URL ?>testimonials/avatar-lucas.jpg" alt="Lucas Fernando">
                                        <span class="client-initial">L</span>
                                    </div>
                                    <div class="client-details">
                                        <h4 class="client-details__name">Lucas Fernando</h4>
                                        <span class="client-details__date">07/07/2024</span>
                                    </div>
                                </div>
                                <div class="google-icon">
                                    <img src="<?= IMAGES_URL ?>google-icon.png" alt="Google Review">
                                </div>
                            </div>
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="testimonial-content">
                                <p class="testimonial-content__text">Excelentes produtos e atendimento de primeira. Encontrei todas as peças que precisava para meu caminhão com facilidade.</p>
                            </div>
                        </div>

                        <div class="testimonial-card">
                            <div class="testimonial-header">
                                <div class="client-info">
                                    <div class="client-avatar">
                                        <img src="<?= IMAGES_URL ?>testimonials/avatar-derick.jpg" alt="Derick Hernandes">
                                        <span class="client-initial">D</span>
                                    </div>
                                    <div class="client-details">
                                        <h4 class="client-details__name">Derick Hernandes</h4>
                                        <span class="client-details__date">01/07/2024</span>
                                    </div>
                                </div>
                                <div class="google-icon">
                                    <img src="<?= IMAGES_URL ?>google-icon.png" alt="Google Review">
                                </div>
                            </div>
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="testimonial-content">
                                <p class="testimonial-content__text">Atendimento rápido e entrega pontual. Comprei peças para a suspensão do meu veículo e chegaram em perfeito estado.</p>
                            </div>
                        </div>

                        <div class="testimonial-card">
                            <div class="testimonial-header">
                                <div class="client-info">
                                    <div class="client-avatar">
                                        <img src="<?= IMAGES_URL ?>testimonials/avatar-cleber.jpg" alt="Cleber Marques">
                                        <span class="client-initial">C</span>
                                    </div>
                                    <div class="client-details">
                                        <h4 class="client-details__name">Cleber Marques</h4>
                                        <span class="client-details__date">26/06/2024</span>
                                    </div>
                                </div>
                                <div class="google-icon">
                                    <img src="<?= IMAGES_URL ?>google-icon.png" alt="Google Review">
                                </div>
                            </div>
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="testimonial-content">
                                <p class="testimonial-content__text">Atendimento pelo WhatsApp muito prático! Consegui tirar todas as dúvidas e fazer meu pedido sem complicação.</p>
                            </div>
                        </div>

                        <div class="testimonial-card">
                            <div class="testimonial-header">
                                <div class="client-info">
                                    <div class="client-avatar">
                                        <img src="<?= IMAGES_URL ?>testimonials/avatar-cassia.jpg" alt="Cássia Nunes">
                                        <span class="client-initial">C</span>
                                    </div>
                                    <div class="client-details">
                                        <h4 class="client-details__name">Cássia Nunes</h4>
                                        <span class="client-details__date">22/06/2024</span>
                                    </div>
                                </div>
                                <div class="google-icon">
                                    <img src="<?= IMAGES_URL ?>google-icon.png" alt="Google Review">
                                </div>
                            </div>
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="testimonial-content">
                                <p class="testimonial-content__text">Produtos de ótima qualidade e durabilidade. Já é a terceira vez que compro e sempre fico satisfeita.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

<!-- Seção para o Mapa do Brasil com as Unidades -->
<section class="locations-section">
    <div class="section-header">
        <h2 class="section-header__title">Nossas Unidades</h2>
        <p class="section-header__description">Atendimento presencial em nossas lojas espalhadas pelo Brasil</p>
    </div>
    
    <div class="locations-container">
        <div class="map-container">
            <div id="storesMap" style="width: 100%; height: 500px;"></div>
        </div>
        
        <div class="stores-list">
            <div class="store-selector">
                <select id="storeSelector" class="store-selector__select">
                    <option value="">Selecione uma loja para ver detalhes</option>
                </select>
            </div>
            
            <div id="storeDetails" class="store-details">
                <!-- Detalhes da loja selecionada serão exibidos aqui -->
                <div class="store-details__placeholder">
                    <p>Selecione uma loja no mapa ou na lista acima para ver os detalhes</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CSS adicional para o mapa -->
<style>
    .locations-container {
        display: flex;
        flex-direction: column;
        gap: 30px;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }
    
    .map-container {
        border-radius: var(--border-radius);
        overflow: hidden;
        box-shadow: var(--box-shadow);
    }
    
    .stores-list {
        background-color: var(--white);
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        padding: 20px;
    }
    
    .store-selector {
        margin-bottom: 20px;
    }
    
    .store-selector__select {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid var(--border-color);
        border-radius: var(--border-radius);
        background-color: var(--white);
        font-family: var(--font-secondary);
        font-size: var(--font-base);
    }
    
    .store-details {
        padding: 15px 0;
    }
    
    .store-card {
        display: none;
    }
    
    .store-card.active {
        display: block;
    }
    
    .store-card__title {
        font-size: var(--font-lg);
        color: var(--primary-color);
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid var(--border-color);
    }
    
    .store-card__item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 12px;
    }
    
    .store-card__item i {
        color: var(--primary-color);
        margin-right: 10px;
        margin-top: 3px;
    }
    
    .store-card__text {
        font-size: var(--font-base);
        color: var(--text-color);
        line-height: 1.5;
    }
    
    .store-card__link {
        display: inline-flex;
        align-items: center;
        background-color: var(--primary-color);
        color: var(--white);
        padding: 8px 15px;
        border-radius: var(--border-radius);
        text-decoration: none;
        margin-top: 15px;
        transition: var(--transition);
    }
    
    .store-card__link:hover {
        background-color: var(--primary-hover);
        transform: translateY(-2px);
        color: var(--white);
    }
    
    .store-card__link i {
        margin-right: 8px;
    }
    
    /* Estilo para o popup do Leaflet */
    .custom-popup {
        margin-bottom: 10px;
    }
    
    .custom-popup h3 {
        font-size: 16px;
        margin-bottom: 5px;
        color: var(--primary-color);
    }
    
    .custom-popup p {
        font-size: 14px;
        margin-bottom: 5px;
    }
    
    .custom-popup-button {
        display: inline-block;
        background-color: var(--primary-color);
        color: white;
        padding: 5px 10px;
        border-radius: 4px;
        text-decoration: none;
        font-size: 12px;
        font-weight: 500;
    }
    
    /* Responsivo */
    @media (min-width: 992px) {
        .locations-container {
            flex-direction: row;
        }
        
        .map-container {
            flex: 1.5;
        }
        
        .stores-list {
            flex: 1;
        }
    }
    
    @media (max-width: 768px) {
        #storesMap {
            height: 400px;
        }
    }
    
    @media (max-width: 576px) {
        #storesMap {
            height: 300px;
        }
    }
</style>

        </div>
    </div>
</main>

<script>
    // Slides de animação das Marcas Parceiras
    document.addEventListener('DOMContentLoaded', function() {
        const brandsTrack = document.querySelector('.brands-track');

        if (brandsTrack) {
            // Clone the current brands to create a seamless loop
            const brandsClone = brandsTrack.innerHTML;
            brandsTrack.innerHTML = brandsClone + brandsClone;

            // Start the animation
            brandsTrack.classList.add('animate');
        }
    });

    // Javascript da area Google Maps "Nossas Unidades"
    const tabButtons = document.querySelectorAll('.units-tabs__button');
    const tabPanes = document.querySelectorAll('.units-tabs__pane');

    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            tabButtons.forEach(btn => btn.classList.remove('units-tabs__button--active'));

            // Add active class to current button
            this.classList.add('units-tabs__button--active');

            // Hide all panes
            tabPanes.forEach(pane => pane.classList.remove('units-tabs__pane--active'));

            // Show the target pane
            const target = this.dataset.target;
            document.getElementById(target).classList.add('units-tabs__pane--active');
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
    // Dados das lojas
    const stores = [
        {
            id: 'loja01',
            name: 'LOJA 01 - Matriz Maringá',
            address: 'R ESTADOS UNIDOS 1423 JARDIM INTERNORTE, Maringá - PR',
            phone: '(44) 3027-7373',
            hours: 'Segunda a Sexta: 8:00 - 18:00 | Sábado: 8:00 - 13:00',
            coords: [-23.398663, -51.919023],
            mapsLink: 'https://maps.app.goo.gl/Zf6241xvQiqg1xN77'
        },
        {
            id: 'loja02',
            name: 'LOJA 02 - Maringá',
            address: 'RODOVIA BR-376 10082 N 130 JD KOSMOS, Maringá - PR',
            phone: '(44) 3027-7373',
            hours: 'Segunda a Sexta: 8:00 - 18:00 | Sábado: 8:00 - 13:00',
            coords: [-23.395072, -51.974118],
            mapsLink: 'https://maps.app.goo.gl/C3MJjLDLxrp57Xv9A'
        },
        {
            id: 'loja03',
            name: 'LOJA 03 - Cambé',
            address: 'RUA JOSÉ CARLOS MUFATTO N 2024 - JARDIM ANA ELIZA II, Cambé - PR',
            phone: '(43) 3174-0600',
            hours: 'Segunda a Sexta: 8:00 - 18:00 | Sábado: 8:00 - 13:00',
            coords: [-23.304381, -51.290531],
            mapsLink: 'https://maps.app.goo.gl/9YF8zQ1D4nAHqJCcA'
        },
        {
            id: 'loja04',
            name: 'LOJA 04 - Luís Eduardo Magalhães',
            address: 'AV LESTE N 67 CIDADE DO AUTOMOVEL, Luís Eduardo Magalhães - BA',
            phone: '(77) 3628-9889',
            hours: 'Segunda a Sexta: 8:00 - 18:00 | Sábado: 8:00 - 13:00',
            coords: [-12.088805, -45.812589],
            mapsLink: 'https://maps.app.goo.gl/X9XZREkbjANwtQQj9'
        },
        {
            id: 'loja08',
            name: 'LOJA 08 - Luís Eduardo Magalhães',
            address: 'RUA PALMAS LOTE 14 QD 99 N 721 BAIRRO BOA VISTA, Luís Eduardo Magalhães - BA',
            phone: '(77) 3628-9880',
            hours: 'Segunda a Sexta: 8:00 - 18:00 | Sábado: 8:00 - 13:00',
            coords: [-12.131923, -45.789805],
            mapsLink: 'https://maps.app.goo.gl/PTCZriUL7yX6yqQW7'
        },
        {
            id: 'loja11',
            name: 'LOJA 11 - Barreiras',
            address: 'AV. PASTOR ANTONIO ULISSES NASCIMENTO 1817 - CIDADE NOVA, Barreiras - BA',
            phone: '(77) 3612-2100',
            hours: 'Segunda a Sexta: 8:00 - 18:00 | Sábado: 8:00 - 13:00',
            coords: [-12.868580, -44.977614],
            mapsLink: 'https://maps.app.goo.gl/4ZXGvUEVUvqwZLBW7'
        },
        {
            id: 'loja09',
            name: 'LOJA 09 - Dourados',
            address: 'RUA JOSÉ BONILHA DA CRUZ 7260 - PARQUE DAS NAÇÕES, Dourados - MS',
            phone: '(67) 3033-5050',
            hours: 'Segunda a Sexta: 8:00 - 18:00 | Sábado: 8:00 - 13:00',
            coords: [-22.232539, -54.809972],
            mapsLink: 'https://maps.app.goo.gl/Rh9i5xUqUNjEggH36'
        },
        {
            id: 'loja06',
            name: 'LOJA 06 - Rondonópolis',
            address: 'AV PERIMETRAL 1469 - TREVÃO, Rondonópolis - MT',
            phone: '(66) 3022-4999',
            hours: 'Segunda a Sexta: 8:00 - 18:00 | Sábado: 8:00 - 13:00',
            coords: [-16.659271, -54.641480],
            mapsLink: 'https://maps.app.goo.gl/dav3ht4M5jUrLJot9'
        },
        {
            id: 'loja07',
            name: 'LOJA 07 - Confresa',
            address: 'AV BRASIL QUADRA 1 LOTE 13 AEROPORTO, Confresa - MT',
            phone: '(66) 3564-1515',
            hours: 'Segunda a Sexta: 8:00 - 18:00 | Sábado: 8:00 - 13:00',
            coords: [-10.645532, -51.569764],
            mapsLink: 'https://maps.app.goo.gl/ZaRPczYa89FBWztn9'
        },
        {
            id: 'loja12',
            name: 'LOJA 12 - Sinop',
            address: 'RUA COLONIZADOR ÊNIO PIPINO 1293 - SETOR INDUSTRIAL SUL, Sinop - MT',
            phone: '(66) 3515-5757',
            hours: 'Segunda a Sexta: 8:00 - 18:00 | Sábado: 8:00 - 13:00',
            coords: [-11.855329, -55.507023],
            mapsLink: 'https://maps.app.goo.gl/eDs9zsz38TFGqUmv7'
        }
    ];
    
    // Inicializar o mapa
    const map = L.map('storesMap').setView([-15.77972, -47.92972], 4); // Centralizado no Brasil
    
    // Adicionar layer de mapa base
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
    
    // Ícone personalizado para os marcadores
    const storeIcon = L.icon({
        iconUrl: `${BASE_URL}/assets/img/map-marker.png`,
        iconSize: [32, 32],
        iconAnchor: [16, 32],
        popupAnchor: [0, -32]
    });
    
    // Adicionar marcadores para cada loja
    const markers = {};
    stores.forEach(store => {
        // Criar marcador
        const marker = L.marker(store.coords, {icon: storeIcon})
            .addTo(map);
        
        // Criar conteúdo do popup
        const popupContent = `
            <div class="custom-popup">
                <h3>${store.name}</h3>
                <p>${store.address}</p>
                <a href="#" class="custom-popup-button store-details-btn" data-store="${store.id}">Ver detalhes</a>
            </div>
        `;
        
        // Adicionar popup ao marcador
        marker.bindPopup(popupContent);
        
        // Armazenar marcador para referência futura
        markers[store.id] = marker;
        
        // Adicionar opção ao seletor de lojas
        const option = document.createElement('option');
        option.value = store.id;
        option.textContent = store.name;
        document.getElementById('storeSelector').appendChild(option);
    });
    
    // Gerar cards de detalhes das lojas
    const storeDetailsContainer = document.getElementById('storeDetails');
    stores.forEach(store => {
        const storeCard = document.createElement('div');
        storeCard.id = `store-card-${store.id}`;
        storeCard.className = 'store-card';
        storeCard.innerHTML = `
            <h3 class="store-card__title">${store.name}</h3>
            <div class="store-card__item">
                <i class="fas fa-map-marker-alt"></i>
                <span class="store-card__text">${store.address}</span>
            </div>
            <div class="store-card__item">
                <i class="fas fa-phone-alt"></i>
                <span class="store-card__text">${store.phone}</span>
            </div>
            <div class="store-card__item">
                <i class="fas fa-clock"></i>
                <span class="store-card__text">${store.hours}</span>
            </div>
            <a href="${store.mapsLink}" class="store-card__link" target="_blank">
                <i class="fas fa-directions"></i> Como chegar
            </a>
        `;
        storeDetailsContainer.appendChild(storeCard);
    });
    
    // Evento para mostrar detalhes da loja quando selecionada no dropdown
    document.getElementById('storeSelector').addEventListener('change', function(e) {
        const storeId = e.target.value;
        
        // Esconder todos os cards
        document.querySelectorAll('.store-card').forEach(card => {
            card.classList.remove('active');
        });
        
        // Mostrar placeholder se nenhuma loja foi selecionada
        if (!storeId) {
            document.querySelector('.store-details__placeholder').style.display = 'block';
            return;
        }
        
        // Esconder placeholder
        document.querySelector('.store-details__placeholder').style.display = 'none';
        
        // Mostrar card selecionado
        document.getElementById(`store-card-${storeId}`).classList.add('active');
        
        // Abrir popup no mapa
        markers[storeId].openPopup();
        
        // Centralizar mapa no marcador
        map.setView(markers[storeId].getLatLng(), 13);
    });
    
    // Evento para os botões "Ver detalhes" nos popups
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('store-details-btn')) {
            e.preventDefault();
            const storeId = e.target.dataset.store;
            
            // Selecionar a loja no dropdown
            document.getElementById('storeSelector').value = storeId;
            
            // Disparar o evento change para mostrar os detalhes
            const event = new Event('change');
            document.getElementById('storeSelector').dispatchEvent(event);
            
            // Scroll para a seção de detalhes
            document.getElementById('storeDetails').scrollIntoView({ behavior: 'smooth' });
        }
    });
    
    // Ajustar o mapa para mostrar todos os marcadores
    const bounds = [];
    stores.forEach(store => {
        bounds.push(store.coords);
    });
    map.fitBounds(bounds);
});
</script>