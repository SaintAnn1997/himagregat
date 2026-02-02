<?php
if (in_array('the-events-calendar/the-events-calendar.php', apply_filters('active_plugins', get_option('active_plugins')))) {

    // add_filter('tribe_event_label_singular', function () {
    //     return 'Событие';
    // });
    // add_filter('tribe_event_label_plural', function () {
    //     return 'События';
    // });

    // Меняем название архива мероприятий
    add_filter('tribe_events_title', function ($title) {
        return $title;
    }, 10, 2);

    // Удаляем кнопку "Добавить в календарь" со страницы события
    add_action('wp', function () {
        if (class_exists('Tribe__Events__iCal')) {
            $ical = tribe('tec.iCal');
            remove_action('tribe_events_single_event_after_the_content', [$ical, 'single_event_links']);
        }
    }, 20);
}
