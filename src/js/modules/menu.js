// Мобильное меню

const menu = (overlay) => {
    const body = document.body;
    const burger = document.querySelector('.header__burger');
    const headerCenter = document.querySelector('.header__center');
    const menuLinks = headerCenter.querySelectorAll('.header__nav a');
    const mobileMenuClose = document.querySelector('.header__mobile-close');

    function openMenu() {
        headerCenter.classList.add('header__center--active');
        body.classList.add('body-active');
        overlay.classList.add('overlay--active');
    }

    function closeMenu() {
        headerCenter.classList.remove('header__center--active');
        body.classList.remove('body-active');
        overlay.classList.remove('overlay--active');
    }

    burger.addEventListener('click', () => {
        openMenu();
    });

    mobileMenuClose.addEventListener('click', () => {
        closeMenu();
    });

    overlay.addEventListener('click', () => {
        closeMenu();
    })

    menuLinks.forEach(link => {
        link.addEventListener('click', () => {
            closeMenu();
        });
    });

}

export default menu;