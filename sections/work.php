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

            <!----------------------Nossas Unidades Section usando o componente da página de contato---------------------->
            <section class="units-section">
                <div class="section-header">
                    <h2 class="section-header__title">Nossas Unidades</h2>
                    <p class="section-header__description">Encontre a unidade mais próxima para envio de currículo presencial ou entrevistas</p>
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