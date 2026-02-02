import { postData } from "./services/requests";

const tabs = document.querySelectorAll('.archive-tabs__item');
const container = document.getElementById('news-container');
const loader = document.getElementById('news-loader');

let currentPage = 1;
let currentCategory = 'all';
const initialPosts = 10;
const postsPerLoad = 5;
let isLoading = false;
let hasMorePosts = true;
let scrollTimeout = null;

function loadNews(page = 1, append = false) {
    if (isLoading) {
        return;
    }
    
    if (!hasMorePosts && append) {
        return;
    }

    isLoading = true;
    if (loader) loader.style.display = '';

    const postsCount = page === 1 ? initialPosts : postsPerLoad;

    postData(
        ajax_object.ajaxurl,
        `action=khag_load_news_by_category&category_id=${currentCategory}&paged=${page}&posts_per_page=${postsCount}&nonce=${ajax_object.nonce}`
    )
        .then(data => {
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = data;
            const items = tempDiv.querySelectorAll('.archive-news__item');

            if (append) {
                items.forEach(item => container.appendChild(item));
            } else {
                container.innerHTML = '';
                items.forEach(item => container.appendChild(item));
            }

            if (items.length < postsCount) {
                hasMorePosts = false;
            } else {
                hasMorePosts = true;
            }
        })
        .catch(error => {
            console.error('Ошибка подгрузки новостей:', error);
        })
        .finally(() => {
            if (loader) loader.style.display = 'none';
            isLoading = false;
        });
}

function checkScroll() {
    if (scrollTimeout) return;
    
    scrollTimeout = setTimeout(() => {
        scrollTimeout = null;
        
        if (isLoading) {
            return;
        }
        
        if (!hasMorePosts) {
            return;
        }

        const containerRect = container.getBoundingClientRect();
        const containerBottom = containerRect.bottom;
        const windowHeight = window.innerHeight;
        const triggerPoint = windowHeight + 500;

        if (containerBottom <= triggerPoint) {
            currentPage++;
            loadNews(currentPage, true);
        }
    }, 200);
}

function initTabs() {
    if (tabs.length > 0 && container) {
        tabs.forEach(tab => {
            tab.addEventListener('click', function () {
                if (this.disabled) return;
                
                const isActive = this.classList.contains('archive-tabs__item--active');
                if (isActive) return;

                const categoryId = this.getAttribute('data-category');
                currentCategory = categoryId;
                currentPage = 1;
                hasMorePosts = true;
                
                tabs.forEach(t => {
                    t.classList.remove('archive-tabs__item--active');
                    t.disabled = true;
                });
                this.classList.add('archive-tabs__item--active');

                if (loader) loader.style.display = '';

                postData(
                    ajax_object.ajaxurl,
                    `action=khag_load_news_by_category&category_id=${categoryId}&paged=1&posts_per_page=${initialPosts}&nonce=${ajax_object.nonce}`
                )
                    .then(data => {
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = data;
                        const items = tempDiv.querySelectorAll('.archive-news__item');

                        container.innerHTML = '';
                        items.forEach(item => container.appendChild(item));

                        if (items.length < initialPosts) {
                            hasMorePosts = false;
                        } else {
                            hasMorePosts = true;
                        }
                    })
                    .catch(error => {
                        console.error('Ошибка загрузки новостей по категории:', error);
                    })
                    .finally(() => {
                        if (loader) loader.style.display = 'none';
                        tabs.forEach(t => t.disabled = false);
                    });
            });
        });
    }
}

if (container) {
    const initialItems = container.querySelectorAll('.archive-news__item');
    
    if (initialItems.length < initialPosts) {
        hasMorePosts = false;
    }
    
    initialLoadComplete = true;
    initTabs();
    window.addEventListener('scroll', checkScroll);
}