<?php

/**
 * Catalog Widget - Виджет каталога оборудования
 */
class Khag_Catalog_Widget extends WP_Widget
{

    /**
     * Настройки виджета
     */
    public function __construct()
    {
        $widget_ops = array(
            'classname'   => 'khag_catalog_widget',
            'description' => __('Виджет каталога оборудования для сайдбара статей', 'khag')
        );

        $control_ops = array(
            'width'   => 500,
            'height'  => 450,
            'id_base' => 'khag_catalog_widget'
        );

        parent::__construct('khag_catalog_widget', __('Виджет каталога', 'khag'), $widget_ops, $control_ops);
    }

    /**
     * Отображение виджета на фронтенде
     */
    public function widget($args, $instance)
    {
        extract($args);

        $title       = !empty($instance['title']) ? $instance['title'] : 'Оборудование для химической промышленности';
        $description = !empty($instance['description']) ? $instance['description'] : 'Проектирование и производство высокотехнологичного оборудования для химических производств любой сложности.';
        $list_items  = !empty($instance['list_items']) ? explode("\n", $instance['list_items']) : array(
            'Реакторы из специальных сплавов',
            'Теплообменное оборудование',
            'Колонное оборудование',
            'Системы автоматизации'
        );
        $button_text = !empty($instance['button_text']) ? $instance['button_text'] : 'Запросить каталог';
        $button_url  = !empty($instance['button_url']) ? $instance['button_url'] : '#';

?>
        <div class="widget-catalog">
            <div class="widget-catalog__title"><?php echo esc_html($title); ?></div>
            <div class="widget-catalog__descr"><?php echo esc_html($description); ?></div>

            <ul class="widget-catalog__list">
                <?php foreach ($list_items as $item) : 
                    $item = trim($item);
                    if (!empty($item)) : ?>
                        <li><?php echo esc_html($item); ?></li>
                    <?php endif; 
                endforeach; ?>
            </ul>

            <a href="<?php echo esc_url($button_url); ?>" class="widget-catalog__btn btn" target="_blank"><?php echo esc_html($button_text); ?></a>
        </div>
<?php
    }

    /**
     * Форма настроек виджета в админке
     */
    public function form($instance)
    {
        $title       = !empty($instance['title']) ? $instance['title'] : 'Оборудование для химической промышленности';
        $description = !empty($instance['description']) ? $instance['description'] : 'Проектирование и производство высокотехнологичного оборудования для химических производств любой сложности.';
        $list_items  = !empty($instance['list_items']) ? $instance['list_items'] : "Реакторы из специальных сплавов\nТеплообменное оборудование\nКолонное оборудование\nСистемы автоматизации";
        $button_text = !empty($instance['button_text']) ? $instance['button_text'] : 'Запросить каталог';
        $button_url  = !empty($instance['button_url']) ? $instance['button_url'] : '#';
?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Заголовок:', 'khag'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('description'); ?>"><?php _e('Описание:', 'khag'); ?></label>
            <textarea class="widefat" rows="3" id="<?php echo $this->get_field_id('description'); ?>" name="<?php echo $this->get_field_name('description'); ?>"><?php echo esc_textarea($description); ?></textarea>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('list_items'); ?>"><?php _e('Список оборудования (каждый пункт с новой строки):', 'khag'); ?></label>
            <textarea class="widefat" rows="5" id="<?php echo $this->get_field_id('list_items'); ?>" name="<?php echo $this->get_field_name('list_items'); ?>"><?php echo esc_textarea($list_items); ?></textarea>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('button_text'); ?>"><?php _e('Текст кнопки:', 'khag'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('button_text'); ?>" name="<?php echo $this->get_field_name('button_text'); ?>" type="text" value="<?php echo esc_attr($button_text); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('button_url'); ?>"><?php _e('Ссылка кнопки:', 'khag'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('button_url'); ?>" name="<?php echo $this->get_field_name('button_url'); ?>" type="url" value="<?php echo esc_url($button_url); ?>" />
        </p>
<?php
    }

    /**
     * Сохранение настроек виджета
     */
    public function update($new_instance, $old_instance)
    {
        $instance = array();
        
        $instance['title']       = !empty($new_instance['title']) ? strip_tags($new_instance['title']) : '';
        $instance['description'] = !empty($new_instance['description']) ? strip_tags($new_instance['description']) : '';
        $instance['list_items']  = !empty($new_instance['list_items']) ? $new_instance['list_items'] : '';
        $instance['button_text'] = !empty($new_instance['button_text']) ? strip_tags($new_instance['button_text']) : '';
        $instance['button_url']  = !empty($new_instance['button_url']) ? esc_url_raw($new_instance['button_url']) : '';

        return $instance;
    }
}

