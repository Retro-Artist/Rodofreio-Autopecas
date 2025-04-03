<?php
// sections/contact.php

$message = '';
$messageClass = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $message_text = trim($_POST['message'] ?? '');
    
    if (empty($name) || empty($email) || empty($message_text)) {
        $message = 'Por favor, preencha todos os campos obrigatórios.';
        $messageClass = 'error';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Por favor, informe um endereço de e-mail válido.';
        $messageClass = 'error';
    } else {
        // Aqui você normalmente enviaria o email
        // Para simulação, apenas mostramos uma mensagem de sucesso
        $message = 'Obrigado por sua mensagem. Entraremos em contato em breve.';
        $messageClass = 'success';
    }
}
?>

<div class="contact-page">
    <!----------------------Hero Banner Section---------------------->
    <section class="hero-banner">
        <div class="hero-overlay"></div>
    </section>

    <div class="contact-page__wrapper">
        <div class="contact-page__content">
            <!----------------------Contact Header Section---------------------->
            <section class="contact-header">
                <div class="section-header">
                    <h1 class="section-header__title">Entre em Contato</h1>
                    <p class="section-header__description">Estamos aqui para responder suas dúvidas, receber sugestões ou atender suas necessidades. Preencha o formulário abaixo ou utilize um de nossos canais de atendimento.</p>
                </div>
                
                <?php if ($message): ?>
                    <div class="message message--<?= $messageClass ?>">
                        <div class="message__icon">
                            <i class="fas fa-<?= $messageClass === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
                        </div>
                        <div class="message__content">
                            <p class="message__text"><?= htmlspecialchars($message) ?></p>
                        </div>
                    </div>
                <?php endif; ?>
            </section>

            <!----------------------Contact Main Section---------------------->
            <section class="contact-main">
                <div class="contact-container">
                    <!-- Contact Information Column -->
                    <div class="contact-info">
                        <div class="contact-info__card">
                            <h2 class="contact-info__title">Informações de Contato</h2>
                            <p class="contact-info__text">Utilize um de nossos canais para entrar em contato com nossa equipe de atendimento.</p>
                            
                            <div class="contact-info__items">
                                <div class="contact-info__item">
                                    <div class="contact-info__icon">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div class="contact-info__content">
                                        <h3 class="contact-info__item-title">Endereço</h3>
                                        <p class="contact-info__item-text">Rua Estados Unidos, 1423 – Jardim Internorte<br>Maringá - PR, CEP 87045-010</p>
                                    </div>
                                </div>
                                
                                <div class="contact-info__item">
                                    <div class="contact-info__icon">
                                        <i class="fas fa-phone-alt"></i>
                                    </div>
                                    <div class="contact-info__content">
                                        <h3 class="contact-info__item-title">Telefone</h3>
                                        <p class="contact-info__item-text">(44) 3027-7373 | (44) 3224-8383</p>
                                    </div>
                                </div>
                                
                                <div class="contact-info__item">
                                    <div class="contact-info__icon">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div class="contact-info__content">
                                        <h3 class="contact-info__item-title">E-mail</h3>
                                        <p class="contact-info__item-text">contato@rodofreios.com.br</p>
                                    </div>
                                </div>
                                
                                <div class="contact-info__item">
                                    <div class="contact-info__icon">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div class="contact-info__content">
                                        <h3 class="contact-info__item-title">Horário de Atendimento</h3>
                                        <p class="contact-info__item-text">Segunda a Sexta: 8:00 - 18:00<br>Sábado: 8:00 - 13:00</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="contact-info__social">
                                <h3 class="contact-info__social-title">Redes Sociais</h3>
                                <div class="contact-info__social-icons">
                                    <a href="https://www.facebook.com/Rodofreios" class="contact-info__social-link" aria-label="Facebook">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                    <a href="https://www.instagram.com/rodofreios_oficial" class="contact-info__social-link" aria-label="Instagram">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                    <a href="#" class="contact-info__social-link" aria-label="YouTube">
                                        <i class="fab fa-youtube"></i>
                                    </a>
                                    <a href="https://wa.me/<?= WHATSAPP_NUMBER ?>" class="contact-info__social-link" aria-label="WhatsApp" target="_blank">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contact Form Column -->
                    <div class="contact-form-container">
                        <div class="contact-form__card">
                            <h2 class="contact-form__title">Envie sua Mensagem</h2>
                            <p class="contact-form__text">Preencha o formulário abaixo com suas informações e em breve entraremos em contato.</p>
                            
                            <form method="POST" class="contact-form">
                                <div class="contact-form__row">
                                    <div class="contact-form__group">
                                        <label for="name" class="contact-form__label">Nome <span class="contact-form__required">*</span></label>
                                        <input type="text" id="name" name="name" class="contact-form__input" required 
                                               value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                                               placeholder="Seu nome completo">
                                    </div>
                                    
                                    <div class="contact-form__group">
                                        <label for="email" class="contact-form__label">E-mail <span class="contact-form__required">*</span></label>
                                        <input type="email" id="email" name="email" class="contact-form__input" required 
                                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                                               placeholder="Seu endereço de e-mail">
                                    </div>
                                </div>
                                
                                <div class="contact-form__row">
                                    <div class="contact-form__group">
                                        <label for="phone" class="contact-form__label">Telefone</label>
                                        <input type="tel" id="phone" name="phone" class="contact-form__input"
                                               value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>"
                                               placeholder="(00) 00000-0000">
                                    </div>
                                    
                                    <div class="contact-form__group">
                                        <label for="subject" class="contact-form__label">Assunto</label>
                                        <input type="text" id="subject" name="subject" class="contact-form__input"
                                               value="<?= htmlspecialchars($_POST['subject'] ?? '') ?>"
                                               placeholder="Do que se trata sua mensagem?">
                                    </div>
                                </div>
                                
                                <div class="contact-form__group">
                                    <label for="message" class="contact-form__label">Mensagem <span class="contact-form__required">*</span></label>
                                    <textarea id="message" name="message" class="contact-form__textarea" required rows="6"
                                             placeholder="Como podemos ajudar você?"><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                                </div>
                                
                                <div class="contact-form__actions">
                                    <button type="submit" class="contact-form__button">
                                        <i class="fas fa-paper-plane"></i>
                                        Enviar Mensagem
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
            
            <!----------------------Unidades Section---------------------->
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
                                        <span>Rua Estados Unidos, 1423 – Jardim Internorte, Maringá - PR, CEP 87045-010</span>
                                    </div>
                                    <div class="unit-info__item">
                                        <i class="fas fa-phone-alt"></i>
                                        <span>(44) 3027-7373 | (44) 3224-8383</span>
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
            
            <!----------------------FAQ Section---------------------->
            <section class="faq-section">
                <div class="section-header">
                    <h2 class="section-header__title">Perguntas Frequentes</h2>
                    <p class="section-header__description">Confira as dúvidas mais comuns de nossos clientes</p>
                </div>
                
                <div class="faq-accordion">
                    <div class="faq-accordion__item">
                        <button class="faq-accordion__button">
                            Como posso comprar peças na Rodofreios?
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-accordion__content">
                            <p>Você pode comprar peças da Rodofreios de várias formas: visitando uma de nossas lojas físicas, pelo telefone, WhatsApp ou entrando em contato pelo formulário em nosso site. Nossa equipe está pronta para atendê-lo.</p>
                        </div>
                    </div>
                    
                    <div class="faq-accordion__item">
                        <button class="faq-accordion__button">
                            Quais formas de pagamento são aceitas?
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-accordion__content">
                            <p>Aceitamos diversas formas de pagamento: dinheiro, PIX, cartões de crédito e débito, boleto bancário e transferência bancária. Para compras corporativas, oferecemos também a opção de faturamento mediante cadastro aprovado.</p>
                        </div>
                    </div>
                    
                    <div class="faq-accordion__item">
                        <button class="faq-accordion__button">
                            Vocês realizam entregas? Qual o prazo e custo?
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-accordion__content">
                            <p>Sim, realizamos entregas para todo o Brasil. O prazo e o custo variam conforme a região e o peso da encomenda. Para compras acima de R$ 300,00 em regiões metropolitanas onde temos lojas físicas, a entrega é gratuita. Entre em contato conosco para obter informações específicas sobre sua região.</p>
                        </div>
                    </div>
                    
                    <div class="faq-accordion__item">
                        <button class="faq-accordion__button">
                            Qual a garantia das peças?
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-accordion__content">
                            <p>Todas as nossas peças possuem garantia de fábrica, que pode variar de 3 meses a 1 ano, dependendo do produto e do fabricante. Além disso, oferecemos suporte técnico para auxiliar na instalação e uso adequado das peças.</p>
                        </div>
                    </div>
                    
                    <div class="faq-accordion__item">
                        <button class="faq-accordion__button">
                            Como proceder em caso de devolução ou troca?
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-accordion__content">
                            <p>Em caso de necessidade de devolução ou troca, entre em contato conosco em até 7 dias após o recebimento da peça. É necessário que o produto esteja em sua embalagem original, sem sinais de uso ou danos. Cada caso será analisado individualmente pela nossa equipe.</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
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
        
        // Accordion functionality for FAQ
        const accordionButtons = document.querySelectorAll('.faq-accordion__button');
        
        accordionButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Toggle active class on the parent item
                this.parentElement.classList.toggle('faq-accordion__item--active');
                
                // Toggle the content visibility
                const content = this.nextElementSibling;
                if (this.parentElement.classList.contains('faq-accordion__item--active')) {
                    content.style.maxHeight = content.scrollHeight + 'px';
                } else {
                    content.style.maxHeight = 0;
                }
            });
        });
    });
</script>
