function updateTabGradients(tabButtons) {
    const activeTab = document.querySelector('.archive-tabs__item--active');
    const tabsContainer = document.querySelector('.archive-tabs');

    if (!activeTab || !tabsContainer || tabButtons.length === 0) return;

    const firstTab = tabButtons[0];
    const lastTab = tabButtons[tabButtons.length - 1];

    tabsContainer.classList.remove('has-active-first', 'has-active-last');

    if (activeTab === firstTab) {
        tabsContainer.classList.add('has-active-first');
    } else if (activeTab === lastTab) {
        tabsContainer.classList.add('has-active-last');
    }
}

export function setActiveTab(tabButtons, targetButton) {
    tabButtons.forEach(btn => btn.classList.remove('archive-tabs__item--active'));

    if (targetButton) {
        targetButton.classList.add('archive-tabs__item--active');
    }
    updateTabGradients(tabButtons);
}

export function showTabsContent(tabsContent, i = 0) {
    tabsContent[i].classList.add('js-tabs-content--active');
}

export function hideTabsContent(tabsContent) {
    tabsContent.forEach(content => {
        content.classList.remove('js-tabs-content--active');
    });
}