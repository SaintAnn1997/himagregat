import menu from './modules/menu.js';
import search from './modules/search.js';
import tabs from './modules/tabs.js'
import journalTabsLinks from './modules/journal-tabs-links.js';
import './modules/ajax-archive-news.js';
import './modules/ajax-magazine-pagination.js';
import subscribe from './modules/form-subscribe.js';


document.addEventListener('DOMContentLoaded', () => {

    const overlay = document.querySelector('.overlay');

    document.addEventListener('wpcf7mailsent', function (event) {
        event.target.reset();
    }, false);

    try {
        tabs('.archive-tabs__wrapper', '.archive-tabs__item', '.js-tabs-content');
    } catch (error) {

    }

    try {
        journalTabsLinks();
    } catch (error) {

    }

    try {
        search();
    } catch (error) {

    }

    try {
        menu(overlay);
    } catch (error) {

    }

    try {
        subscribe();
    } catch (error) {

    }

});


