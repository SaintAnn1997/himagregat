<?php

/**
 * Events Widget - Виджет календаря событий
 */
class Khag_Events_Widget extends WP_Widget
{

    /**
     * Настройки виджета
     */
    public function __construct()
    {
        $widget_ops = array(
            'classname'   => 'khag_events_widget',
            'description' => __('Виджет календаря событий (последние 4 мероприятия)', 'khag')
        );

        $control_ops = array(
            'width'   => 400,
            'height'  => 350,
            'id_base' => 'khag_events_widget'
        );

        parent::__construct('khag_events_widget', __('Виджет событий', 'khag'), $widget_ops, $control_ops);
    }

    /**
     * Отображение виджета на фронтенде
     */
    public function widget($args, $instance)
    {
        extract($args);

        $title = !empty($instance['title']) ? $instance['title'] : 'Календарь событий';
        $events_count = !empty($instance['events_count']) ? (int)$instance['events_count'] : 4;

        // Получаем ближайшие события
        if (function_exists('tribe_get_events')) {
            $events = tribe_get_events([
                'posts_per_page' => $events_count,
                'post_status'    => ['publish', 'future'],
                'ends_after'     => current_time('Y-m-d H:i:s'),
                'orderby'        => 'start_date',
                'order'          => 'ASC',
            ]);
        } else {
            $events = array();
        }

?>
        <div class="widget-events">
            <div class="widget-events__title"><?php echo esc_html($title); ?></div>

            <?php if (!empty($events)) : ?>
                <ul class="widget-events__list">
                    <?php foreach ($events as $event) :
                        $event_id = $event->ID;

                        // Форматируем даты (день или диапазон дней)
                        $start_day = tribe_get_start_date($event_id, false, 'j');
                        $end_day = tribe_get_end_date($event_id, false, 'j');
                        $month = khag_month_genitive(tribe_get_start_date($event_id, false, 'F'));

                        // Определяем диапазон дат
                        $date_range = '';
                        if ($start_day && $end_day) {
                            if ($start_day === $end_day) {
                                $date_range = $start_day;
                            } else {
                                $date_range = $start_day . '-' . $end_day;
                            }
                        }

                        $city = tribe_get_city($event_id);

                        $title_event = get_field('short_title_event', $event_id);
                        if (empty($title_event)) {
                            $title_event = get_the_title($event_id);
                        }

                        $permalink = get_permalink($event_id);
                    ?>
                        <li>
                            <a href="<?php echo esc_url($permalink); ?>" target="_blank" class="widget-events__list-item">
                                <div class="widget-events__list-date">
                                    <span><?php echo esc_html($date_range); ?></span> <?php echo esc_html($month); ?>
                                </div>
                                <div class="widget-events__list-info">
                                    <?php if ($city) : ?>
                                        <div class="widget-events__list-city"><?php echo esc_html($city); ?></div>
                                    <?php endif; ?>
                                    <div class="widget-events__list-name"><?php echo esc_html($title_event); ?></div>
                                </div>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <a href="<?php echo esc_url(home_url('/events/')); ?>" class="widget-events__more">Все события</a>
            <?php else : ?>
                <p>Нет предстоящих событий</p>
            <?php endif; ?>

        </div>
    <?php
    }

    /**
     * Форма настроек виджета в админке
     */
    public function form($instance)
    {
        $title = !empty($instance['title']) ? $instance['title'] : 'Календарь событий';
        $events_count = !empty($instance['events_count']) ? $instance['events_count'] : 4;
    ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Заголовок:', 'khag'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('events_count'); ?>"><?php _e('Количество событий:', 'khag'); ?></label>
            <input class="tiny-text" id="<?php echo $this->get_field_id('events_count'); ?>" name="<?php echo $this->get_field_name('events_count'); ?>" type="number" min="1" max="10" value="<?php echo esc_attr($events_count); ?>" />
        </p>
<?php
    }

    /**
     * Сохранение настроек виджета
     */
    public function update($new_instance, $old_instance)
    {
        $instance = array();

        $instance['title'] = !empty($new_instance['title']) ? strip_tags($new_instance['title']) : '';
        $instance['events_count'] = !empty($new_instance['events_count']) ? (int)$new_instance['events_count'] : 4;

        return $instance;
    }
}
