<?php

function khag_widgets_init()
{
    // Сайдбар для виджетов на странице отдельной статьи
    register_sidebar(
        array(
            'name'          => 'Сайдбар для статьи',
            'id'            => 'sidebar-single',
            'description'   => 'Сайдбар для виджетов на странице отдельной статьи',
            'before_widget' => '',
            'after_widget'  => '',
            'before_title'  => '',
            'after_title'   => '',
        )
    );

    register_sidebar(
        array(
            'name'          => 'Сайдбар для архива журнала на странице "Журнал"',
            'id'            => 'sidebar-archive-journal',
            'description'   => 'Сайдбар для виджетов для архива журнала на странице "Журнал"',
            'before_widget' => '',
            'after_widget'  => '',
            'before_title'  => '',
            'after_title'   => '',
        )
    );

    register_sidebar(
        array(
            'name'          => 'Сайдбар в секции "Техника и технологии"',
            'id'            => 'sidebar-archive-technic',
            'description'   => 'Сайдбар для секции "Техника и технологии"',
            'before_widget' => '',
            'after_widget'  => '',
            'before_title'  => '',
            'after_title'   => '',
        )
    );

        register_sidebar(
        array(
            'name'          => 'Сайдбар на странице "Журнал" для вкладок "О журнале", "Подписка", "Реклама"',
            'id'            => 'sidebar-journal',
            'description'   => 'Сайдбар на странице "Журнал" для вкладок "О журнале", "Подписка", "Реклама"',
            'before_widget' => '',
            'after_widget'  => '',
            'before_title'  => '',
            'after_title'   => '',
        )
    );

    // Регистрируем виджеты
    register_widget('Khag_Catalog_Widget');
    register_widget('Khag_Contacts_Widget');
    register_widget('Khag_Events_Widget');
    register_widget('Khag_Journal_Notice_Widget');
    register_widget('Khag_Subscribe_Widget');
}

add_action('widgets_init', 'khag_widgets_init');
