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
                            <button class="units-tabs__button" data-target="loja03">Londrina - PR</button>
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
                                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3660.7882532063823!2d-51.92184682494766!3d-23.399090360497053!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94ecd723ec4a4865%3A0x4f5f2c1a56f9ef46!2sR.%20Estados%20Unidos%2C%201423%20-%20Jardim%20Internorte%2C%20Maring%C3%A1%20-%20PR%2C%2087045-010!5e0!3m2!1spt-BR!2sbr!4v1712264134901!5m2!1spt-BR!2sbr"
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
                                            <span>R. Estados Unidos, 1423 - Jardim Internorte, Maringá - PR, 87045-010</span>
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
                                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3660.633986433242!2d-51.97626822494766!3d-23.394941060453143!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94ecd6da4d61c277%3A0xbe0cc4b5a0fc675a!2sAv.%20Colombo%2C%2010082%20-%20Ch%C3%A1caras%20Estilo%2C%20Maring%C3%A1%20-%20PR%2C%2087070-120!5e0!3m2!1spt-BR!2sbr!4v1712264298232!5m2!1spt-BR!2sbr"
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
                                            <span>Av. Colombo, 10082 - Chácaras Estilo, Maringá - PR, 87070-120</span>
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

                            <!-- Loja 03 - Londrina -->
                            <div class="units-tabs__pane" id="loja03">
                                <div class="unit-map">
                                    <iframe
                                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3659.0266578066404!2d-51.29268462494529!3d-23.30426550986139!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94eb4394ea257aab%3A0x26a9e7a62f20a428!2sR.%20Jos%C3%A9%20Carlos%20Muffato%2C%201924%20-%20Conj.%20Hab.%20Jamile%20Dequech%2C%20Londrina%20-%20PR%2C%2086044-766!5e0!3m2!1spt-BR!2sbr!4v1712264370431!5m2!1spt-BR!2sbr"
                                        width="100%"
                                        height="300"
                                        style="border:0;"
                                        allowfullscreen=""
                                        loading="lazy"
                                        referrerpolicy="no-referrer-when-downgrade">
                                    </iframe>
                                </div>
                                <div class="unit-info">
                                    <h3 class="unit-info__title">LOJA 03 - Londrina</h3>
                                    <div class="unit-info__details">
                                        <div class="unit-info__item">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span>R. José Carlos Muffato, 1924 - Conj. Hab. Jamile Dequech, Londrina - PR, 86044-766</span>
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

                            <!-- Loja 04 - Luís Eduardo Magalhães (BR-242) -->
                            <div class="units-tabs__pane" id="loja04">
                                <div class="unit-map">
                                    <iframe
                                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3879.4727246766537!2d-45.81535463420349!3d-12.0887104309389!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x934a7b8e1356bfdd%3A0xa9c86c10b5c49d3c!2sRodofreios%20Auto%20Pe%C3%A7as!5e0!3m2!1spt-BR!2sbr!4v1712277035831!5m2!1spt-BR!2sbr"
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
                                            <span>Rod, BR-242, 22 - Chacaras Botelho, Luís Eduardo Magalhães - BA, 47850-000</span>
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


                            <!-- Loja 08 - Luís Eduardo Magalhães (Boa Vista) -->
                            <div class="units-tabs__pane" id="loja08">
                                <div class="unit-map">
                                    <iframe
                                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3879.325462068722!2d-45.79451923420413!3d-12.132269930976086!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x934a71d7e6656859%3A0x72e1edac443f25e!2sRodofreios%20Auto%20Pe%C3%A7as!5e0!3m2!1spt-BR!2sbr!4v1712277133158!5m2!1spt-BR!2sbr"
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
                                            <span>RUA ARNALDO HORACIO FERREIRA, 90 - COMERCIAL, Luís Eduardo Magalhães - BA, 47850-000</span>
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
                                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3900.6815860532306!2d-44.9517795239601!3d-12.133925143531696!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x75f8ba135773de1%3A0x3f90c74ceab6387e!2sRODOFREIOS%20AUTO%20PE%C3%87AS!5e0!3m2!1spt-PT!2sbr!4v1743774456221!5m2!1spt-PT!2sbr"
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
                                            <span>Av. Pastor Antônio Ulisses Nascimento - CIDADE NOVA, Barreiras - BA, 47804-101</span>
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
                                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3693.11714691442!2d-54.78462872374088!3d-22.235633614231908!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x948907f3d91500a7%3A0x7be9d68fe0d25c56!2sRODOFREIOS%20AUTO%20PE%C3%87AS%20(Auto%20Pe%C3%A7as%20Shigenaga)!5e0!3m2!1spt-PT!2sbr!4v1743774492557!5m2!1spt-PT!2sbr"
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
                                            <span>R. José do Patrocínio - BONILHA DA CRUZ, Dourados - MS, 79840-182</span>
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
                                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3828.8057842797843!2d-54.6435896!3d-16.6592249!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x9379c6c1f2d5af6f%3A0x45f34a822f64eab3!2sAv.%20Perimetral%2C%201469%20-%20Vila%20Goulart%2C%20Rondon%C3%B3polis%20-%20MT%2C%2078745-270!5e0!3m2!1spt-BR!2sbr!4v1712264733865!5m2!1spt-BR!2sbr"
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
                                            <span>Av. Perimetral, 1469 - Vila Goulart, Rondonópolis - MT, 78745-270</span>
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
                                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3921.178113817575!2d-51.56853822398414!3d-10.643267117361237!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x931a097dd4427f19%3A0x7464d1ca8de2db8a!2sRODOFREIOS%20AUTO%20PE%C3%87AS!5e0!3m2!1spt-PT!2sbr!4v1743774526992!5m2!1spt-PT!2sbr"
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
                                            <span>AV BRASIL QD 01 LOTA 13 - SETOR AEROPORTO, Confresa - MT, 78652-000</span>
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
                                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3887.9979487577214!2d-55.5092147!3d-11.8552648!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x93a91fb2c2f33929%3A0x77d0a50e4ab4be8a!2sR.%20Colonizador%20%C3%8Anio%20Pipino%2C%201293%20-%20St.%20Industrial%20Sul%2C%20Sinop%20-%20MT%2C%2078557-477!5e0!3m2!1spt-BR!2sbr!4v1712264886705!5m2!1spt-BR!2sbr"
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
                                            <span>R. Colonizador Ênio Pipino, 1293 - St. Industrial Sul, Sinop - MT, 78557-477</span>
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
        // Selecionando os botões de abas e os painéis
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