<?php
// sections/work.php

// Verificar se foi enviado um formulário de currículo
$cvMessage = '';
$cvMessageClass = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['curriculum'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $position = trim($_POST['position'] ?? '');
    $curriculum = $_FILES['curriculum'] ?? null;
    
    // Validação simples
    if (empty($name) || empty($email) || empty($curriculum['name'])) {
        $cvMessage = 'Por favor, preencha todos os campos obrigatórios.';
        $cvMessageClass = 'error';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $cvMessage = 'Por favor, informe um endereço de e-mail válido.';
        $cvMessageClass = 'error';
    } else {
        // Verificar tipo de arquivo (apenas PDF)
        $fileType = strtolower(pathinfo($curriculum['name'], PATHINFO_EXTENSION));
        if ($fileType != "pdf") {
            $cvMessage = 'Somente arquivos PDF são aceitos.';
            $cvMessageClass = 'error';
        } else {
            // Aqui você normalmente faria o upload do arquivo
            // Para simulação, apenas mostramos uma mensagem de sucesso
            $cvMessage = 'Currículo enviado com sucesso! Entraremos em contato em breve.';
            $cvMessageClass = 'success';
        }
    }
}
?>

<div class="work-page">
    <!----------------------Hero Banner Section---------------------->
    <section class="hero-banner">
        <div class="hero-overlay"></div>
        <div class="whatsapp-floating">
            <a href="https://wa.me/<?= WHATSAPP_NUMBER ?>" target="_blank">
                <i class="fab fa-whatsapp"></i>
            </a>
        </div>
    </section>

    <div class="work-page__wrapper">
        <div class="work-page__content">
            <!----------------------Work Header Section---------------------->
            <section class="work-header">
                <div class="section-header">
                    <h1 class="section-header__title">Trabalhe Conosco</h1>
                    <p class="section-header__description">Estamos sempre em busca de profissionais talentosos e dedicados para fazer parte da nossa equipe. Conheça as oportunidades e envie seu currículo.</p>
                </div>
            </section>

            <!----------------------Careers (Trabalhe Conosco) Section---------------------->
            <section class="careers-section">
                
                <?php if ($cvMessage): ?>
                    <div class="message message--<?= $cvMessageClass ?>">
                        <div class="message__icon">
                            <i class="fas fa-<?= $cvMessageClass === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
                        </div>
                        <div class="message__content">
                            <p class="message__text"><?= htmlspecialchars($cvMessage) ?></p>
                        </div>
                    </div>
                <?php endif; ?>
                
                <div class="careers-container">
                    <div class="careers-form-container">
                        <div class="careers-form__card">
                            <h3 class="careers-form__title">Envie seu Currículo</h3>
                            <p class="careers-form__text">Preencha o formulário abaixo e envie seu currículo. Ficaremos felizes em analisar seu perfil para nossas vagas.</p>
                            
                            <form method="POST" enctype="multipart/form-data" class="careers-form">
                                <div class="careers-form__row">
                                    <div class="careers-form__group">
                                        <label for="name" class="careers-form__label">Nome <span class="careers-form__required">*</span></label>
                                        <input type="text" id="name" name="name" class="careers-form__input" required 
                                               value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                                               placeholder="Seu nome completo">
                                    </div>
                                    
                                    <div class="careers-form__group">
                                        <label for="email" class="careers-form__label">E-mail <span class="careers-form__required">*</span></label>
                                        <input type="email" id="email" name="email" class="careers-form__input" required 
                                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                                               placeholder="Seu endereço de e-mail">
                                    </div>
                                </div>
                                
                                <div class="careers-form__row">
                                    <div class="careers-form__group">
                                        <label for="phone" class="careers-form__label">Telefone</label>
                                        <input type="tel" id="phone" name="phone" class="careers-form__input"
                                               value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>"
                                               placeholder="(00) 00000-0000">
                                    </div>
                                    
                                    <div class="careers-form__group">
                                        <label for="position" class="careers-form__label">Cargo desejado</label>
                                        <input type="text" id="position" name="position" class="careers-form__input"
                                               value="<?= htmlspecialchars($_POST['position'] ?? '') ?>"
                                               placeholder="Ex: Vendedor, Mecânico, Administrativo">
                                    </div>
                                </div>
                                
                                <div class="careers-form__group">
                                    <label for="curriculum" class="careers-form__label">Currículo (PDF) <span class="careers-form__required">*</span></label>
                                    <div class="careers-form__file-input">
                                        <input type="file" id="curriculum" name="curriculum" class="careers-form__file" required accept=".pdf">
                                        <div class="careers-form__file-button">
                                            <i class="fas fa-upload"></i>
                                            <span>Escolher arquivo</span>
                                        </div>
                                        <span class="careers-form__file-name">Nenhum arquivo selecionado</span>
                                    </div>
                                    <p class="careers-form__file-help">Somente arquivos PDF são aceitos. Tamanho máximo: 5MB</p>
                                </div>
                                
                                <div class="careers-form__actions">
                                    <button type="submit" class="careers-form__button">
                                        <i class="fas fa-paper-plane"></i>
                                        Enviar Currículo
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="careers-info">
                        <div class="careers-info__card">
                            <h3 class="careers-info__title">Informações Adicionais</h3>
                            <p class="careers-info__text">Entre em contato diretamente com nosso departamento de RH para mais informações sobre oportunidades de carreira.</p>
                            
                            <div class="careers-info__items">
                                <div class="careers-info__item">
                                    <div class="careers-info__icon">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div class="careers-info__content">
                                        <h3 class="careers-info__item-title">E-mail RH</h3>
                                        <p class="careers-info__item-text">rh@rodofreios.com.br</p>
                                    </div>
                                </div>
                                
                                <div class="careers-info__item">
                                    <div class="careers-info__icon">
                                        <i class="fas fa-phone-alt"></i>
                                    </div>
                                    <div class="careers-info__content">
                                        <h3 class="careers-info__item-title">Telefone RH</h3>
                                        <p class="careers-info__item-text">(44) 3027-7373 (Ramal 123)</p>
                                    </div>
                                </div>
                                
                                <div class="careers-info__item">
                                    <div class="careers-info__icon">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div class="careers-info__content">
                                        <h3 class="careers-info__item-title">Horário de Atendimento</h3>
                                        <p class="careers-info__item-text">Segunda a Sexta: 8:00 - 18:00</p>
                                    </div>
                                </div>
                                
                                <div class="careers-info__item">
                                    <div class="careers-info__icon">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div class="careers-info__content">
                                        <h3 class="careers-info__item-title">Endereço</h3>
                                        <p class="careers-info__item-text">Rua Estados Unidos, 1423 – Jardim Internorte<br>Maringá - PR, CEP 87045-010</p>
                                    </div>
                                </div>
                            </div>
    
                        </div>
                    </div>
                </div>
            </section>

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
                            <button class="units-tabs__button" data-target="loja04">(1) Luís Eduardo - BA</button>
                            <button class="units-tabs__button" data-target="loja08">(2) Luís Eduardo - BA</button>
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
                                    <h3 class="unit-info__title">LOJA 08 - Luís Eduardo Magalhães</h3>
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
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // File input styling
        const fileInput = document.getElementById('curriculum');
        const fileNameDisplay = document.querySelector('.careers-form__file-name');
        
        if (fileInput && fileNameDisplay) {
            fileInput.addEventListener('change', function() {
                if (this.files.length > 0) {
                    fileNameDisplay.textContent = this.files[0].name;
                } else {
                    fileNameDisplay.textContent = 'Nenhum arquivo selecionado';
                }
            });
        }
        
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