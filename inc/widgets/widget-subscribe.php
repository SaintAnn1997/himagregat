<?php

/**
 * Subscribe Widget – форма подписки
 */
class Khag_Subscribe_Widget extends WP_Widget
{
    public function __construct()
    {
        $widget_ops  = [
            'classname'   => 'khag_subscribe_widget',
            'description' => __('Виджет формы подписки на журнал', 'khag'),
        ];

        $control_ops = [
            'width'   => 400,
            'height'  => 200,
            'id_base' => 'khag_subscribe_widget',
        ];

        parent::__construct(
            'khag_subscribe_widget',
            __('Виджет подписки', 'khag'),
            $widget_ops,
            $control_ops
        );
    }

    public function widget($args, $instance)
    {
        $title = ! empty($instance['title'])
            ? $instance['title']
            : __('Подписка на журнал', 'khag');

        $description = ! empty($instance['description'])
            ? $instance['description']
            : __('Получайте свежие выпуски журнала “Химагрегаты” с доставкой. Будьте в курсе последних новостей и тенденций отрасли.', 'khag');

        echo $args['before_widget'];
?>
        <div class="widget-subscribe">
            <?php if (! empty($title)) : ?>
                <div class="widget-subscribe__title"><?php echo esc_html($title); ?></div>
            <?php endif; ?>

            <?php if (! empty($description)) : ?>
                <div class="widget-subscribe__descr">
                    <?php echo esc_html($description); ?>
                </div>
            <?php endif; ?>

            <form class="widget-subscribe__form">
                <input name="name" type="text" placeholder="<?php esc_attr_e('Ваше имя', 'khag'); ?>" autocomplete="name" required>
                <input name="email" type="email" placeholder="<?php esc_attr_e('E-mail', 'khag'); ?>" autocomplete="email" required>
                <div class="widget-subscribe__form-checkbox">
                    <input type="checkbox" id="subscribe_consent" name="consent" required>
                    <label for="subscribe_consent">Нажимая кнопку, я даю <a href="<?php echo get_permalink(965); ?>" target="_blank">согласие на обработку моих персональных данных</a> в соответствии с <a href="<?php echo get_permalink(3); ?>" target="_blank">Политикой конфиденциальности</a> и принимаю её.</label>
                </div>
                <div class="subscribe-block__honeypot" aria-hidden="true">
                    <label class="subscribe-block__honeypot-label" for="subscribe_hp_widget">
                        <?php esc_html_e('Оставьте это поле пустым', 'khag'); ?>
                    </label>
                    <input type="text" id="subscribe_hp_widget" name="subscribe_hp" tabindex="-1" autocomplete="off">
                </div>
                <input type="hidden" name="subscribe_nonce" value="<?php echo esc_attr(wp_create_nonce('khag_subscribe_nonce')); ?>">
                <button class="widget-subscribe__btn btn"><?php esc_html_e('Оформить подписку', 'khag'); ?></button>
            </form>
        </div>
    <?php
        echo $args['after_widget'];
    }

    public function form($instance)
    {
        $title = ! empty($instance['title']) ? $instance['title'] : __('Подписка на журнал', 'khag');
        $description = ! empty($instance['description'])
            ? $instance['description']
            : __('Получайте свежие выпуски журнала “Химагрегаты” с доставкой. Будьте в курсе последних новостей и тенденций отрасли.', 'khag');
    ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>">
                <?php esc_html_e('Заголовок:', 'khag'); ?>
            </label>
            <input class="widefat"
                id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                name="<?php echo esc_attr($this->get_field_name('title')); ?>"
                type="text"
                value="<?php echo esc_attr($title); ?>">
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('description')); ?>">
                <?php esc_html_e('Описание:', 'khag'); ?>
            </label>
            <textarea class="widefat" rows="4"
                id="<?php echo esc_attr($this->get_field_id('description')); ?>"
                name="<?php echo esc_attr($this->get_field_name('description')); ?>"><?php echo esc_textarea($description); ?></textarea>
        </p>
<?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance = [];
        $instance['title'] = ! empty($new_instance['title']) ? sanitize_text_field($new_instance['title']) : '';
        $instance['description'] = ! empty($new_instance['description']) ? sanitize_textarea_field($new_instance['description']) : '';

        return $instance;
    }
}
