<?php
// sections/footer.php
?>
</main><!-- Close main-content -->
</div><!-- Close page-wrapper -->

<footer class="footer">
    <div class="footer-top">
        <div class="container">
            <div class="footer-row">
                <div class="footer-column footer-column--wide">
                    <div class="footer-logo">
                        <img src="<?= IMAGES_URL ?>logo-white.webp" alt="<?= SITE_NAME ?>" class="footer-logo-img">
                    </div>
                    <p class="footer-description">Com mais de 32 anos de experiência, somos especializados em peças automotivas para veículos nacionais e importados, oferecendo qualidade, preço justo e atendimento diferenciado em nossas lojas no Brasil.</p>
                    <div class="footer-social">
                        <a href="https://www.facebook.com/Rodofreios" class="social-link" aria-label="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://www.instagram.com/rodofreios_oficial" class="social-link" aria-label="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="social-link" aria-label="YouTube">
                            <i class="fab fa-youtube"></i>
                        </a>
                        <a href="https://wa.me/77998168668" class="social-link social-link--whatsapp" aria-label="WhatsApp" target="_blank">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                    <div class="footer-payments">
                        <h4 class="footer-payments__title">Formas de pagamento:</h4>
                        <div class="footer-payments__img">
                            <img src="<?= IMAGES_URL ?>payment-methods.png" alt="Métodos de pagamento aceitos" class="payment-img">
                        </div>
                    </div>
                </div>
                <div class="footer-column">
                    <h3 class="footer-title">Links Rápidos</h3>
                    <ul class="footer-links">
                        <li><a href="<?= BASE_URL ?>/">Página Inicial</a></li>
                        <li><a href="<?= BASE_URL ?>/produtos">Produtos</a></li>
                        <li><a href="<?= BASE_URL ?>/sobre">Sobre Nós</a></li>
                        <li><a href="<?= BASE_URL ?>/trabalhe-conosco">Trabalhe Conosco</a></li>
                        <li><a href="<?= BASE_URL ?>/contato">Contato</a></li>
                        <li><a href="<?= BASE_URL ?>/privacidade">Política de Privacidade</a></li>
                        <li><a href="<?= BASE_URL ?>/termos">Termos e Condições</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3 class="footer-title">Categorias</h3>
                    <ul class="footer-links">
                        <li><a href="<?= BASE_URL ?>/produtos/categoria/freios">Sistema de Freios</a></li>
                        <li><a href="<?= BASE_URL ?>/produtos/categoria/suspensao">Suspensão</a></li>
                        <li><a href="<?= BASE_URL ?>/produtos/categoria/motor">Motor</a></li>
                        <li><a href="<?= BASE_URL ?>/produtos/categoria/transmissao">Transmissão</a></li>
                        <li><a href="<?= BASE_URL ?>/produtos/categoria/filtros">Filtros</a></li>
                        <li><a href="<?= BASE_URL ?>/produtos/categoria/acessorios">Acessórios</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3 class="footer-title">Contato</h3>
                    <ul class="footer-contact">
                        <li>
                            <i class="fas fa-map-marker-alt"></i>
                            <span><strong>Matriz:</strong> Rua Estados Unidos, 1423 – Jardim Internorte, Maringá – PR, CEP 87045-010</span>
                        </li>
                        <li>
                            <i class="fas fa-phone-alt"></i>
                            <span>(44) 3027-7373 | (44) 3224-8383</span>
                        </li>
                        <li>
                            <i class="fas fa-envelope"></i>
                            <span>contato@rodofreios.com.br</span>
                        </li>
                        <li>
                            <i class="fas fa-clock"></i>
                            <span>Seg - Sex: 8:00 - 18:00 | Sábado: 8:00 - 13:00</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <div class="footer-bottom-content">
                <div class="copyright">
                    <p>&copy; <?= date('Y') ?> <?= SITE_NAME ?> - Todos os direitos reservados.</p>
                    <p class="company-info">CNPJ: 07.903.104/0001-55 | Inscrição Estadual: 90367192-03</p>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-attribution">
        <div class="container">
            <p>Desenvolvido pela <a href="#" target="_blank">Ubidigital</a></p>
        </div>
    </div>
</footer>
<div class="whatsapp-floating">
    <a href="https://wa.me/77998168668" target="_blank">
        <div class="whatsapp-tooltip">Fale conosco pelo WhatsApp</div>
        <i class="fab fa-whatsapp"></i>
    </a>
</div>
<div class="back-to-top" id="backToTop">
    <i class="fas fa-chevron-up"></i>
</div>
<!-- Scripts -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mobile menu toggle
        const mobileMenuButton = document.querySelector('.mobile-menu-toggle');
        const mainNavMenu = document.querySelector('.main-nav__menu');

        if (mobileMenuButton && mainNavMenu) {
            mobileMenuButton.addEventListener('click', function() {
                mainNavMenu.classList.toggle('active');
            });
        }

        // Back to top functionality
        const backToTopButton = document.getElementById('backToTop');

        if (backToTopButton) {
            // Show button after scrolling down 300px
            window.addEventListener('scroll', function() {
                if (window.pageYOffset > 300) {
                    backToTopButton.classList.add('visible');
                } else {
                    backToTopButton.classList.remove('visible');
                }
            });

            // Scroll to top when clicked
            backToTopButton.addEventListener('click', function() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        }
    });
</script>

<script>
    const WHATSAPP_NUMBER = '<?= WHATSAPP_NUMBER ?>';
</script>
<script src="<?= BASE_URL ?>/assets/scripts/loader.js"></script>
<script src="<?= BASE_URL ?>/assets/scripts/cart.js"></script>
<script src="<?= BASE_URL ?>/assets/scripts/mobile-menu.js"></script>
</body>

</html>