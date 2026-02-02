import { setActiveTab, hideTabsContent, showTabsContent } from './tabs-utils.js';

const tabs = (tabsContainerSelector, tabsSelector, tabsContentSelector) => {

    const tabsContainer = document.querySelector(tabsContainerSelector);
    const tabs = document.querySelectorAll(tabsSelector);
    const tabsContent = document.querySelectorAll(tabsContentSelector);

    if (tabsContent.length !== 0) {
        showTabsContent(tabsContent, 0);
    }

    tabsContainer.addEventListener('click', (e) => {
        e.preventDefault();
        const target = e.target;

        if (target && target.classList.contains(tabsSelector.substring(1))) {
            tabs.forEach((tab, i) => {
                if (target === tab) {

                    setActiveTab(tabs, tabs[i]);

                    if (tabsContent.length === 0) {
                        return;
                    } else {
                        hideTabsContent(tabsContent);
                        showTabsContent(tabsContent, i);
                    }

                }
            })
        }

    })



}

export default tabs;