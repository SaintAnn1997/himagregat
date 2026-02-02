import { setActiveTab, showTabsContent, hideTabsContent } from './tabs-utils.js';

const journalTabsLinks = () => {
    const tabButtons = document.querySelectorAll('.archive-tabs__item');
    const tabSections = {
        'О журнале': '.info-section',
        'Архив': '.journal-section--archive',
        'Реклама': '.info-section--ads',
        'Подписка': '.info-section--subscribe'
    };

    const paramToTab = {
        'about': 'О журнале',
        'archive': 'Архив',
        'ads': 'Реклама',
        'subscribe': 'Подписка'
    };

    const tabToParam = {
        'О журнале': 'about',
        'Архив': 'archive',
        'Реклама': 'ads',
        'Подписка': 'subscribe',
    };

    const allSections = Object.values(tabSections).map(selector => document.querySelector(selector));

    const activateTab = (tabName) => {
        const targetIndex = Array.from(tabButtons).findIndex(btn => btn.textContent.trim() === tabName);
        
        if (targetIndex === -1) return;

        setActiveTab(tabButtons, tabButtons[targetIndex]);
        hideTabsContent(allSections);
        
        if (allSections[targetIndex]) {
            showTabsContent(allSections, targetIndex);
        }
    };

    if (tabButtons.length > 0) {
        const urlParams = new URLSearchParams(window.location.search);
        const tabParam = urlParams.get('tab');

        if (tabParam && paramToTab[tabParam]) {
            activateTab(paramToTab[tabParam]);
            
            setTimeout(() => {
                const tabsContainer = document.querySelector('.archive-tabs');
                if (tabsContainer) {
                    tabsContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }, 100);
        } else {
            allSections.forEach((section, index) => {
                if (section) {
                    if (index === 0) {
                        showTabsContent(allSections, 0);
                    }
                }
            });
        }
    }

    const menuLinks = document.querySelectorAll('.footer__nav-list li a');

    menuLinks.forEach(link => {
        const linkText = link.textContent.trim();

        if (tabToParam[linkText]) {
            const tabUrl = `${window.location.origin}/about/?tab=${tabToParam[linkText]}`;
            link.setAttribute('href', tabUrl);

            link.addEventListener('click', (e) => {
                if (e.ctrlKey || e.metaKey || e.shiftKey || e.button === 1) {
                    return;
                }

                const journalTabsContainer = document.querySelector('.archive-tabs--journal');
                
                if (journalTabsContainer && tabButtons.length > 0) {
                    e.preventDefault();
                    activateTab(linkText);
                    
                    setTimeout(() => {
                        journalTabsContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }, 100);
                }
            });
        }
    });
};

export default journalTabsLinks;
