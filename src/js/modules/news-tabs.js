// Простые табы для архива новостей

import { updateTabGradients, setActiveTab } from './utils/tab-gradients.js';

const newsTabs = () => {
    const tabButtons = document.querySelectorAll('.archive-tabs__item');
    
    if (tabButtons.length === 0) return;

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            setActiveTab(tabButtons, button);
        });
    });

    updateTabGradients(tabButtons);
};

export default newsTabs;
