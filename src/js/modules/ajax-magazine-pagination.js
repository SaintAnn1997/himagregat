import { postData } from "./services/requests";

const magazineContainer = document.querySelector('#magazine-items-container');
const paginationContainer = document.querySelector('#magazine-pagination-container');
const loader = document.getElementById('news-loader');

let isLoading = false;

function loadMagazines(page = 1) {
    if (isLoading) return;
    
    isLoading = true;
    if (loader) loader.style.display = '';

    postData(
        ajax_object.ajaxurl,
        `action=khag_load_magazines&paged=${page}&nonce=${ajax_object.nonce}`
    )
        .then(data => {
            if (magazineContainer) {
                const temp = document.createElement('div');
                temp.innerHTML = data;
            
                const items = temp.querySelectorAll('.journal-section__item');
                
                const oldItems = magazineContainer.querySelectorAll('.journal-section__item');
                oldItems.forEach((item, index) => {
                    if (index < oldItems.length - 1) {
                        item.remove();
                    }
                });
                
                const sidebar = magazineContainer.querySelector('.journal-section__item:last-child');
                
                items.forEach(item => {
                    if (sidebar) {
                        sidebar.insertAdjacentElement('beforebegin', item);
                    } else {
                        magazineContainer.appendChild(item);
                    }
                });
                
                const pagination = temp.querySelector('.pagination');
                if (paginationContainer) {
                    paginationContainer.innerHTML = '';
                    if (pagination) {
                        paginationContainer.appendChild(pagination);
                    }
                }
                
                initPaginationHandlers();
                
                const section = document.querySelector('.journal-section--archive');
                if (section) {
                    section.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }
        })
        .catch(error => {
            console.error('Ошибка загрузки журналов:', error);
        })
        .finally(() => {
            if (loader) loader.style.display = 'none';
            isLoading = false;
        });
}

function initPaginationHandlers() {
    if (!paginationContainer) return;
    
    const links = paginationContainer.querySelectorAll('.pagination__item a');
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const page = parseInt(this.getAttribute('data-page'));
            if (page) {
                loadMagazines(page);
            }
        });
    });
}

if (magazineContainer && paginationContainer) {
    const initialPagination = magazineContainer.querySelector('.pagination');
    if (initialPagination) {
        paginationContainer.appendChild(initialPagination);
    }
    initPaginationHandlers();
}

export { loadMagazines, initPaginationHandlers };
