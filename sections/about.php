<?php
// sections/about.php
?>

<div class="about-page">
    <!----------------------Hero Banner Section---------------------->
    <section class="hero-banner">
        <div class="hero-overlay"></div>
        <div class="whatsapp-floating">
            <a href="https://wa.me/<?= WHATSAPP_NUMBER ?>" target="_blank">
                <i class="fab fa-whatsapp"></i>
            </a>
        </div>
    </section>

    <div class="about-page__wrapper">
        <div class="about-page__content">
            <!----------------------About Header Section---------------------->
            <section class="about-header">
                <div class="section-header">
                    <h1 class="section-header__title">Sobre a Rodofreios</h1>
                    <p class="section-header__description">Há mais de 32 anos oferecendo peças automotivas de alta qualidade para veículos nacionais e importados</p>
                </div>
            </section>

            <!----------------------História Section---------------------->
            <section class="about-history">
                <div class="about-container">
                    <div class="about-content">
                        <div class="about-text-image">
                            <div class="about-text">
                                <h2 class="about-content__title">Nossa História</h2>
                                <p class="about-content__text">A Rodofreios foi fundada no ano de 1992 na cidade de Maringá, iniciou suas atividades com o nome de Ribeiro Auto Peças na Av. Colombo, hoje conta com 5 lojas nos estados do Paraná, Mato Grosso e Bahia, comercializando peças para motor, câmbio, diferencial, freios entre muitas outras para caminhões e carretas.</p>
                                
                                <p class="about-content__text">Ao longo das últimas três décadas, nos consolidamos como uma das mais respeitadas empresas no segmento de autopeças para veículos pesados, fornecendo produtos de alta qualidade, atendimento especializado e suporte técnico diferenciado para nossos clientes.</p>
                                
                                <div class="about-cta">
                                    <a href="<?= BASE_URL ?>/?page=work" class="cta-button">
                                        <i class="fas fa-briefcase"></i>
                                        Trabalhe Conosco
                                    </a>
                                </div>
                            </div>
                            <div class="about-image">
                                <img src="<?= IMAGES_URL ?>main-preview.jpg" alt="Loja Rodofreios Autopeças">
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!----------------------Missão, Visão e Valores Section---------------------->
            <section class="about-mvv">
                <div class="section-header">
                    <h2 class="section-header__title">Missão, Visão e Valores</h2>
                </div>
                <div class="about-cards">
                    <div class="about-card">
                        <div class="card-icon">
                            <img src="<?= IMAGES_URL ?>icon-mission.svg" alt="Missão">
                        </div>
                        <h3 class="about-card__title">Missão</h3>
                        <p class="about-card__text">Fornecer soluções em autopeças com qualidade, preço justo e atendimento diferenciado, contribuindo para a segurança e eficiência do transporte de nossos clientes.</p>
                    </div>
                    
                    <div class="about-card">
                        <div class="card-icon">
                            <img src="<?= IMAGES_URL ?>icon-vision.svg" alt="Visão">
                        </div>
                        <h3 class="about-card__title">Visão</h3>
                        <p class="about-card__text">Ser referência nacional no comércio de autopeças para veículos pesados, reconhecida pela excelência em produtos, serviços e relacionamento com clientes e parceiros.</p>
                    </div>
                    
                    <div class="about-card">
                        <div class="card-icon">
                            <img src="<?= IMAGES_URL ?>icon-values.svg" alt="Valores">
                        </div>
                        <h3 class="about-card__title">Valores</h3>
                        <p class="about-card__text">Ética, comprometimento, transparência, respeito ao cliente, qualidade e inovação são os pilares que sustentam nossas ações e relacionamentos comerciais.</p>
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
                            <p class="years-experience__text">Desde 1992, trabalhamos com as melhores peças automotivas para veículos de todas as categorias.</p>
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
                                <h3 class="benefit-content__title">Equipe de Especialistas</h3>
                                <p class="benefit-content__description">Nossa equipe de especialistas está sempre à disposição para oferecer orientações técnicas e ajudar você a encontrar a peça ideal para seu veículo.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!----------------------Nossas Unidades Section usando o componente da página de contato---------------------->
            <section class="units-section">
                <div class="section-header">
                    <h2 class="section-header__title">Nossas Unidades</h2>
                    <p class="section-header__description">Atendimento presencial em nossas lojas espalhadas pelo Brasil</p>
                </div>
                
                <div class="units-tabs">
                    <div class="units-tabs__header">
                        <button class="units-tabs__button units-tabs__button--active" data-target="maringa">Maringá - PR</button>
                        <button class="units-tabs__button" data-target="londrina">Londrina - PR</button>
                        <button class="units-tabs__button" data-target="cuiaba">Cuiabá - MT</button>
                        <button class="units-tabs__button" data-target="salvador">Salvador - BA</button>
                    </div>
                    
                    <div class="units-tabs__content">
                        <div class="units-tabs__pane units-tabs__pane--active" id="maringa">
                            <div class="unit-map">
                                <iframe
                                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3660.4481262589344!2d-51.93804!3d-23.425478!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjPCsDI1JzMxLjciUyA1McKwNTYnMTYuOSJX!5e0!3m2!1spt-BR!2sbr!4v1616682157654!5m2!1spt-BR!2sbr"
                                    width="100%"
                                    height="300"
                                    style="border:0;"
                                    allowfullscreen=""
                                    loading="lazy">
                                </iframe>
                            </div>
                            <div class="unit-info">
                                <h3 class="unit-info__title">Matriz Maringá</h3>
                                <div class="unit-info__details">
                                    <div class="unit-info__item">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>Av. Colombo, 1500 - Centro, Maringá - PR</span>
                                    </div>
                                    <div class="unit-info__item">
                                        <i class="fas fa-phone-alt"></i>
                                        <span>(44) 3333-4444</span>
                                    </div>
                                    <div class="unit-info__item">
                                        <i class="fas fa-clock"></i>
                                        <span>Segunda a Sexta: 8:00 - 18:00 | Sábado: 8:00 - 13:00</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="units-tabs__pane" id="londrina">
                            <div class="unit-map">
                                <iframe
                                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3660.4481262589344!2d-51.93804!3d-23.425478!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjPCsDI1JzMxLjciUyA1McKwNTYnMTYuOSJX!5e0!3m2!1spt-BR!2sbr!4v1616682157654!5m2!1spt-BR!2sbr"
                                    width="100%"
                                    height="300"
                                    style="border:0;"
                                    allowfullscreen=""
                                    loading="lazy">
                                </iframe>
                            </div>
                            <div class="unit-info">
                                <h3 class="unit-info__title">Filial Londrina</h3>
                                <div class="unit-info__details">
                                    <div class="unit-info__item">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>Av. Tiradentes, 1000 - Zona Sul, Londrina - PR</span>
                                    </div>
                                    <div class="unit-info__item">
                                        <i class="fas fa-phone-alt"></i>
                                        <span>(43) 3333-4444</span>
                                    </div>
                                    <div class="unit-info__item">
                                        <i class="fas fa-clock"></i>
                                        <span>Segunda a Sexta: 8:00 - 18:00 | Sábado: 8:00 - 13:00</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="units-tabs__pane" id="cuiaba">
                            <div class="unit-map">
                                <iframe
                                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3660.4481262589344!2d-51.93804!3d-23.425478!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjPCsDI1JzMxLjciUyA1McKwNTYnMTYuOSJX!5e0!3m2!1spt-BR!2sbr!4v1616682157654!5m2!1spt-BR!2sbr"
                                    width="100%"
                                    height="300"
                                    style="border:0;"
                                    allowfullscreen=""
                                    loading="lazy">
                                </iframe>
                            </div>
                            <div class="unit-info">
                                <h3 class="unit-info__title">Filial Cuiabá</h3>
                                <div class="unit-info__details">
                                    <div class="unit-info__item">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>Av. Fernando Corrêa, 3000 - Coxipó, Cuiabá - MT</span>
                                    </div>
                                    <div class="unit-info__item">
                                        <i class="fas fa-phone-alt"></i>
                                        <span>(65) 3333-4444</span>
                                    </div>
                                    <div class="unit-info__item">
                                        <i class="fas fa-clock"></i>
                                        <span>Segunda a Sexta: 8:00 - 18:00 | Sábado: 8:00 - 13:00</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="units-tabs__pane" id="salvador">
                            <div class="unit-map">
                                <iframe
                                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3660.4481262589344!2d-51.93804!3d-23.425478!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjPCsDI1JzMxLjciUyA1McKwNTYnMTYuOSJX!5e0!3m2!1spt-BR!2sbr!4v1616682157654!5m2!1spt-BR!2sbr"
                                    width="100%"
                                    height="300"
                                    style="border:0;"
                                    allowfullscreen=""
                                    loading="lazy">
                                </iframe>
                            </div>
                            <div class="unit-info">
                                <h3 class="unit-info__title">Filial Salvador</h3>
                                <div class="unit-info__details">
                                    <div class="unit-info__item">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>Av. Tancredo Neves, 2500 - Caminho das Árvores, Salvador - BA</span>
                                    </div>
                                    <div class="unit-info__item">
                                        <i class="fas fa-phone-alt"></i>
                                        <span>(71) 3333-4444</span>
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
            </section>

            <!----------------------Brands/Partners Section---------------------->
            <section class="brands-section">
                <div class="section-header">
                    <h2 class="section-header__title">Marcas Parceiras</h2>
                    <p class="section-header__description">Trabalhamos com as melhores marcas do mercado para garantir qualidade e confiabilidade</p>
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
        </div>
    </div>
</div>

<script>
    // Simple brands slider animation
    document.addEventListener('DOMContentLoaded', function() {
        const brandsTrack = document.querySelector('.brands-track');

        if (brandsTrack) {
            // Clone the current brands to create a seamless loop
            const brandsClone = brandsTrack.innerHTML;
            brandsTrack.innerHTML = brandsClone + brandsClone;

            // Start the animation
            brandsTrack.classList.add('animate');
        }
        
        // Tabs functionality for units
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