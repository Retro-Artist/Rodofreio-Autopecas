<?php
// sections/product_unavailable.php
// Página exibida quando um produto não está disponível ou publicado
?>

<div class="error-page product-unavailable">
    <div class="container">
        <div class="error-content">
            <div class="error-icon">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <h1 class="error-title">Produto Indisponível</h1>
            <div class="error-message">
                <p>O produto que você está buscando não está disponível no momento ou não foi publicado.</p>
                <p>Isso pode ocorrer pelos seguintes motivos:</p>
                <ul>
                    <li>O produto foi temporariamente retirado do catálogo</li>
                    <li>O produto está em fase de atualização</li>
                    <li>O link que você acessou está incorreto ou desatualizado</li>
                </ul>
            </div>
            <div class="error-actions">
                <a href="<?= BASE_URL ?>/produtos" class="primary-button">
                    <i class="fas fa-search"></i> Ver Todos os Produtos
                </a>
                <a href="<?= BASE_URL ?>/" class="secondary-button">
                    <i class="fas fa-home"></i> Voltar para a Página Inicial
                </a>
                <a href="<?= BASE_URL ?>/contato" class="secondary-button">
                    <i class="fas fa-envelope"></i> Entre em Contato
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .product-unavailable {
        padding: 60px 0;
        text-align: center;
        background-color: var(--color-bg-light);
        min-height: 50vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .error-content {
        max-width: 600px;
        margin: 0 auto;
        background-color: var(--color-content-bg);
        border-radius: var(--border-radius);
        padding: 40px;
        box-shadow: var(--box-shadow);
    }
    
    .error-icon {
        font-size: 60px;
        color: #dc3545;
        margin-bottom: 20px;
    }
    
    .error-title {
        font-size: var(--font-2xl);
        font-family: var(--font-secondary);
        color: #dc3545;
        margin-bottom: 20px;
    }
    
    .error-message {
        margin-bottom: 30px;
        color: var(--color-text-dark);
    }
    
    .error-message p {
        margin-bottom: 15px;
        font-size: var(--font-base);
    }
    
    .error-message ul {
        text-align: left;
        max-width: 400px;
        margin: 15px auto;
        padding-left: 20px;
    }
    
    .error-message li {
        margin-bottom: 8px;
        font-size: var(--font-base);
        list-style-type: disc;
    }
    
    .error-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        justify-content: center;
    }
    
    .primary-button {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background-color: var(--color-accent);
        color: var(--color-text-light);
        padding: 12px 20px;
        border-radius: var(--border-radius);
        font-weight: 500;
        text-decoration: none;
        transition: background-color 0.3s;
    }
    
    .primary-button:hover {
        background-color: #2980b9;
    }
    
    .secondary-button {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background-color: #f8f9fa;
        color: var(--color-text-dark);
        padding: 12px 20px;
        border-radius: var(--border-radius);
        font-weight: 500;
        text-decoration: none;
        border: 1px solid #dee2e6;
        transition: background-color 0.3s;
    }
    
    .secondary-button:hover {
        background-color: #e9ecef;
    }
    
    @media (max-width: 768px) {
        .product-unavailable {
            padding: 40px 20px;
        }
        
        .error-content {
            padding: 30px 20px;
        }
        
        .error-actions {
            flex-direction: column;
        }
        
        .primary-button, .secondary-button {
            width: 100%;
            justify-content: center;
        }
    }
</style>