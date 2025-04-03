// Arquivo: assets/scripts/mobile-menu.js
document.addEventListener('DOMContentLoaded', function() {
    // Referências aos elementos
    const menuButton = document.querySelector('.mobile-menu-toggle');
    const mobileMenu = document.querySelector('.main-nav__menu');
    
    // Verificar se os elementos existem
    if (menuButton && mobileMenu) {
        // Adicionar evento de clique ao botão do menu
        menuButton.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Toggle da classe 'active' no menu
            mobileMenu.classList.toggle('active');
            this.classList.toggle('active');
            
            console.log('Menu mobile toggled');
        });
    }
    
    // Evento de clique para os dropdowns no mobile
    const dropdownLinks = document.querySelectorAll('.main-nav__item--dropdown > .main-nav__link');
    
    dropdownLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            if (window.innerWidth <= 768) {
                e.preventDefault();
                e.stopPropagation();
                
                const parentLi = this.parentNode;
                parentLi.classList.toggle('active');
                console.log('Dropdown toggled');
            }
        });
    });
    
    // Fechar o menu quando clicar fora dele, mas não fechar quando clicar na área de busca
    document.addEventListener('click', function(event) {
        // Verificar se o clique foi fora do menu e do botão de menu
        if (!event.target.closest('.main-nav__menu') && 
            !event.target.closest('.mobile-menu-toggle') && 
            !event.target.closest('.mobile-search-wrapper') && 
            mobileMenu.classList.contains('active')) {
            mobileMenu.classList.remove('active');
            menuButton.classList.remove('active');
            console.log('Menu closed by outside click');
        }
    });
});