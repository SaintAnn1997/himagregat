const search = () => {

    // Поиск в шапке
    const searchToggles = document.querySelectorAll('.js-search-toggle');
    const searchBar = document.querySelector('.header__search-bar');
    const searchInput = document.querySelector('.header__search-input');
    const searchClose = document.querySelector('.header__search-close');

    if (searchToggles.length === 0 || !searchBar) return;

    function toggleSearchBar() {
        searchToggles.forEach(toggle => {
            toggle.classList.toggle('header__info-search--active');
        });
        searchBar.classList.toggle('header__search-bar--active');
    }

    function closeSearchBar() {
        searchToggles.forEach(toggle => {
            toggle.classList.remove('header__info-search--active');
        });
        searchBar.classList.remove('header__search-bar--active');
        if (searchInput) {
            searchInput.value = '';
        }
    }

    searchToggles.forEach(toggle => {
        toggle.addEventListener('click', (e) => {
            e.preventDefault();

            toggleSearchBar();

            setTimeout(() => {
                if (searchInput) {
                    searchInput.focus();
                }
            }, 400);
        });
    });

    if (searchClose) {
        searchClose.addEventListener('click', (e) => {
            e.preventDefault();

            closeSearchBar();
        });
    }

}

export default search;
