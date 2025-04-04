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
                    <h2 class="section-header__title">Trabalhamos Com</h2>
                </div>

                <div class="brands-slider">
                    <div class="brands-track">
                        <div class="brand-item">
                            <img src="<?= IMAGES_URL ?>marca-02.png" alt="Marca Parceira">
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
                            <img src="<?= IMAGES_URL ?>marca-08.png" alt="Marca Parceira">
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
                                    <p class="feature-content__text">Trabalhamos apenas com marcas homologadas e peças originais ou de primeira linha, garantindo durabilidade e performance para seu transporte pesado.</p>
                                </div>
                            </div>

                            <div class="quality-feature">
                                <div class="feature-icon">
                                    <img src="<?= IMAGES_URL ?>icon-service.png" alt="Ícone de atendimento">
                                </div>
                                <div class="feature-content">
                                    <h3 class="feature-content__title">Suporte Técnico Especializado</h3>
                                    <p class="feature-content__text">Nossa equipe de especialistas oferece atendimento personalizado, ajudando você a encontrar a peça perfeita e garantindo a melhor solução para sua necessidade específica.</p>
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

<!----------------------Units Section---------------------->
<section class="units-section">
    <div class="section-header">
        <h2 class="section-header__title">Nossas Unidades</h2>
        <p class="section-header__description">Atendimento presencial em nossas lojas espalhadas pelo Brasil</p>
    </div>
    
    <div class="units-tabs units-tabs--sidebar">
        <div class="units-tabs__container">
            <!-- Sidebar com as abas laterais -->
            <div class="units-tabs__sidebar">
                <button class="units-tabs__button units-tabs__button--active" data-target="loja01">Maringá - PR (Matriz)</button>
                <button class="units-tabs__button" data-target="loja02">Maringá - PR</button>
                <button class="units-tabs__button" data-target="loja03">Cambé - PR</button>
                <button class="units-tabs__button" data-target="loja04">Luís Eduardo - BA</button>
                <button class="units-tabs__button" data-target="loja08">L. Eduardo - BA (Boa Vista)</button>
                <button class="units-tabs__button" data-target="loja11">Barreiras - BA</button>
                <button class="units-tabs__button" data-target="loja09">Dourados - MS</button>
                <button class="units-tabs__button" data-target="loja06">Rondonópolis - MT</button>
                <button class="units-tabs__button" data-target="loja07">Confresa - MT</button>
                <button class="units-tabs__button" data-target="loja12">Sinop - MT</button>
            </div>

            <!-- Conteúdo dos mapas e informações -->
            <div class="units-tabs__content">
                <!-- Loja 01 - Maringá (Matriz) -->
                <div class="units-tabs__pane units-tabs__pane--active" id="loja01">
                    <div class="unit-map">
                        <iframe
                            src="https://www.google.com/maps/embed?q=-23.399132,-51.919142&z=17"
                            width="100%"
                            height="300"
                            style="border:0;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                    <div class="unit-info">
                        <h3 class="unit-info__title">LOJA 01 - Matriz Maringá</h3>
                        <div class="unit-info__details">
                            <div class="unit-info__item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>R ESTADOS UNIDOS 1423 JARDIM INTERNORTE, Maringá - PR</span>
                            </div>
                            <div class="unit-info__item">
                                <i class="fas fa-phone-alt"></i>
                                <span>(44) 3027-7373</span>
                            </div>
                            <div class="unit-info__item">
                                <i class="fas fa-clock"></i>
                                <span>Segunda a Sexta: 8:00 - 18:00 | Sábado: 8:00 - 13:00</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Loja 02 - Maringá -->
                <div class="units-tabs__pane" id="loja02">
                    <div class="unit-map">
                        <iframe
                            src="https://www.google.com/maps/embed?q=-23.394964,-51.973733&z=17"
                            width="100%"
                            height="300"
                            style="border:0;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                    <div class="unit-info">
                        <h3 class="unit-info__title">LOJA 02 - Maringá</h3>
                        <div class="unit-info__details">
                            <div class="unit-info__item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>RODOVIA BR-376 10082 N 130 JD KOSMOS, Maringá - PR</span>
                            </div>
                            <div class="unit-info__item">
                                <i class="fas fa-phone-alt"></i>
                                <span>(44) 3027-7373</span>
                            </div>
                            <div class="unit-info__item">
                                <i class="fas fa-clock"></i>
                                <span>Segunda a Sexta: 8:00 - 18:00 | Sábado: 8:00 - 13:00</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Loja 03 - Cambé -->
                <div class="units-tabs__pane" id="loja03">
                    <div class="unit-map">
                        <iframe
                            src="https://www.google.com/maps/embed?q=-23.304264,-51.290156&z=17"
                            width="100%"
                            height="300"
                            style="border:0;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                    <div class="unit-info">
                        <h3 class="unit-info__title">LOJA 03 - Cambé</h3>
                        <div class="unit-info__details">
                            <div class="unit-info__item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>RUA JOSÉ CARLOS MUFATTO N 2024 - JARDIM ANA ELIZA II, Cambé - PR</span>
                            </div>
                            <div class="unit-info__item">
                                <i class="fas fa-phone-alt"></i>
                                <span>(43) 3174-0600</span>
                            </div>
                            <div class="unit-info__item">
                                <i class="fas fa-clock"></i>
                                <span>Segunda a Sexta: 8:00 - 18:00 | Sábado: 8:00 - 13:00</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Loja 04 - Luís Eduardo Magalhães -->
                <div class="units-tabs__pane" id="loja04">
                    <div class="unit-map">
                        <iframe
                            src="https://www.google.com/maps/embed?q=-12.088780,-45.812416&z=17"
                            width="100%"
                            height="300"
                            style="border:0;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                    <div class="unit-info">
                        <h3 class="unit-info__title">LOJA 04 - Luís Eduardo Magalhães</h3>
                        <div class="unit-info__details">
                            <div class="unit-info__item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>AV LESTE N 67 CIDADE DO AUTOMOVEL, Luís Eduardo Magalhães - BA</span>
                            </div>
                            <div class="unit-info__item">
                                <i class="fas fa-phone-alt"></i>
                                <span>(77) 3628-9889</span>
                            </div>
                            <div class="unit-info__item">
                                <i class="fas fa-clock"></i>
                                <span>Segunda a Sexta: 8:00 - 18:00 | Sábado: 8:00 - 13:00</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Loja 08 - Luís Eduardo Magalhães 2 -->
                <div class="units-tabs__pane" id="loja08">
                    <div class="unit-map">
                        <iframe
                            src="https://www.google.com/maps/embed?q=-12.131896,-45.789513&z=17"
                            width="100%"
                            height="300"
                            style="border:0;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                    <div class="unit-info">
                        <h3 class="unit-info__title">LOJA 08 - Luís Eduardo Magalhães (Boa Vista)</h3>
                        <div class="unit-info__details">
                            <div class="unit-info__item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>RUA PALMAS LOTE 14 QD 99 N 721 BAIRRO BOA VISTA, Luís Eduardo Magalhães - BA</span>
                            </div>
                            <div class="unit-info__item">
                                <i class="fas fa-phone-alt"></i>
                                <span>(77) 3628-9880</span>
                            </div>
                            <div class="unit-info__item">
                                <i class="fas fa-clock"></i>
                                <span>Segunda a Sexta: 8:00 - 18:00 | Sábado: 8:00 - 13:00</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Loja 11 - Barreiras -->
                <div class="units-tabs__pane" id="loja11">
                    <div class="unit-map">
                        <iframe
                            src="https://www.google.com/maps/embed?q=-12.868518,-44.977263&z=17"
                            width="100%"
                            height="300"
                            style="border:0;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                    <div class="unit-info">
                        <h3 class="unit-info__title">LOJA 11 - Barreiras</h3>
                        <div class="unit-info__details">
                            <div class="unit-info__item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>AV. PASTOR ANTONIO ULISSES NASCIMENTO 1817 - CIDADE NOVA, Barreiras - BA</span>
                            </div>
                            <div class="unit-info__item">
                                <i class="fas fa-phone-alt"></i>
                                <span>(77) 3612-2100</span>
                            </div>
                            <div class="unit-info__item">
                                <i class="fas fa-clock"></i>
                                <span>Segunda a Sexta: 8:00 - 18:00 | Sábado: 8:00 - 13:00</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Loja 09 - Dourados -->
                <div class="units-tabs__pane" id="loja09">
                    <div class="unit-map">
                        <iframe
                            src="https://www.google.com/maps/embed?q=-22.232450,-54.809601&z=17"
                            width="100%"
                            height="300"
                            style="border:0;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                    <div class="unit-info">
                        <h3 class="unit-info__title">LOJA 09 - Dourados</h3>
                        <div class="unit-info__details">
                            <div class="unit-info__item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>RUA JOSÉ BONILHA DA CRUZ 7260 - PARQUE DAS NAÇÕES, Dourados - MS</span>
                            </div>
                            <div class="unit-info__item">
                                <i class="fas fa-phone-alt"></i>
                                <span>(67) 3033-5050</span>
                            </div>
                            <div class="unit-info__item">
                                <i class="fas fa-clock"></i>
                                <span>Segunda a Sexta: 8:00 - 18:00 | Sábado: 8:00 - 13:00</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Loja 06 - Rondonópolis -->
                <div class="units-tabs__pane" id="loja06">
                    <div class="unit-map">
                        <iframe
                            src="https://www.google.com/maps/embed?q=-16.659225,-54.641013&z=17"
                            width="100%"
                            height="300"
                            style="border:0;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                    <div class="unit-info">
                        <h3 class="unit-info__title">LOJA 06 - Rondonópolis</h3>
                        <div class="unit-info__details">
                            <div class="unit-info__item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>AV PERIMETRAL 1469 - TREVÃO, Rondonópolis - MT</span>
                            </div>
                            <div class="unit-info__item">
                                <i class="fas fa-phone-alt"></i>
                                <span>(66) 3022-4999</span>
                            </div>
                            <div class="unit-info__item">
                                <i class="fas fa-clock"></i>
                                <span>Segunda a Sexta: 8:00 - 18:00 | Sábado: 8:00 - 13:00</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Loja 07 - Confresa -->
                <div class="units-tabs__pane" id="loja07">
                    <div class="unit-map">
                        <iframe
                            src="https://www.google.com/maps/embed?q=-10.645470,-51.569375&z=17"
                            width="100%"
                            height="300"
                            style="border:0;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                    <div class="unit-info">
                        <h3 class="unit-info__title">LOJA 07 - Confresa</h3>
                        <div class="unit-info__details">
                            <div class="unit-info__item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>AV BRASIL QUADRA 1 LOTE 13 AEROPORTO, Confresa - MT</span>
                            </div>
                            <div class="unit-info__item">
                                <i class="fas fa-phone-alt"></i>
                                <span>(66) 3564-1515</span>
                            </div>
                            <div class="unit-info__item">
                                <i class="fas fa-clock"></i>
                                <span>Segunda a Sexta: 8:00 - 18:00 | Sábado: 8:00 - 13:00</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Loja 12 - Sinop -->
                <div class="units-tabs__pane" id="loja12">
                    <div class="unit-map">
                        <iframe
                            src="https://www.google.com/maps/embed?q=-11.855265,-55.506640&z=17"
                            width="100%"
                            height="300"
                            style="border:0;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                    <div class="unit-info">
                        <h3 class="unit-info__title">LOJA 12 - Sinop</h3>
                        <div class="unit-info__details">
                            <div class="unit-info__item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>RUA COLONIZADOR ÊNIO PIPINO 1293 - SETOR INDUSTRIAL SUL, Sinop - MT</span>
                            </div>
                            <div class="unit-info__item">
                                <i class="fas fa-phone-alt"></i>
                                <span>(66) 3515-5757</span>
                            </div>
                            <div class="unit-info__item">
                                <i class="fas fa-clock"></i>
                                <span>Segunda a Sexta: 8:00 - 18:00 | Sábado: 8:00 - 13:00</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
            
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



    document.addEventListener('DOMContentLoaded', function() {
    // Selecionando os botões de abas e os painéis (funciona tanto para abas horizontais quanto verticais)
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
});
</script>