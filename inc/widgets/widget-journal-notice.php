<?php

/**
 * Widget: Journal Notice
 * Виджет оповещения о новом выпуске журнала
 */

class Khag_Journal_Notice_Widget extends WP_Widget
{

    public function __construct()
    {
        parent::__construct(
            'khag_journal_notice',
            'Оповещение о журнале',
            array('description' => 'Показывает оповещение о новом выпуске журнала')
        );
    }

    // Форма настроек виджета в админке
    public function form($instance)
    {
?>
        <p>
            <small><strong>Автоматическая работа:</strong> Виджет показывает последний опубликованный журнал. Просто добавьте новый журнал в админке, и он появится здесь.</small>
        </p>
    <?php
    }

    // Сохранение настроек
    public function update($new_instance, $old_instance)
    {
        return array();
    }

    // Вывод виджета на фронтенде
    public function widget($args, $instance)
    {
        // Получаем последний опубликованный журнал
        $latest_magazine = get_posts(array(
            'post_type' => 'magazine',
            'posts_per_page' => 1,
            'post_status' => 'publish',
            'orderby' => 'date',
            'order' => 'DESC'
        ));

        if (empty($latest_magazine)) {
            return; // Нет журналов
        }

        $magazine = $latest_magazine[0];
        $magazine_title = get_the_title($magazine->ID);
        $magazine_date = get_the_date('F Y', $magazine->ID);
        $magazine_link = get_field('journal_pdf_file', $magazine->ID);

        $notice_text = sprintf('Вышел новый выпуск журнала "%s" - за %s', esc_html($magazine_title), esc_html($magazine_date));

        echo $args['before_widget'];
    ?>
        <div class="journal-notice journal-notice--sidebar">
            <div class="journal-notice__text">
                <?php echo esc_html($notice_text); ?>
            </div>
            <a href="<?php echo esc_url($magazine_link); ?>" target="_blank" rel="noopener noreferrer" class="journal-notice__btn btn">
                Читать журнал
            </a>
        </div>
<?php
        echo $args['after_widget'];
    }
}
