<?php
/**
 * Contacts Widget - Виджет контактов редакции
 */
class Khag_Contacts_Widget extends WP_Widget
{
    public function __construct()
    {
        $widget_ops = array(
            'classname'   => 'khag_contacts_widget',
            'description' => __('Виджет контактов редакции для сайдбара', 'khag')
        );
        parent::__construct('khag_contacts_widget', __('Виджет контактов', 'khag'), $widget_ops);
    }

    public function widget($args, $instance)
    {
        extract($args);
        
        // Получаем title из настроек виджета или используем значение по умолчанию
        $title = !empty($instance['title']) ? $instance['title'] : 'Контакты редакции';
        
        // Получаем контакты из ACF опций
        $address = get_field('contact_address', 'option');
        $phone = get_field('contact_tel', 'option');
        $email = get_field('contact_mail', 'option');
?>
        <div class="widget-contacts widget-contacts--sidebar">
            <div class="widget-contacts__title"><?php echo esc_html($title); ?></div>
            
            <?php if ($address) : ?>
                <div class="widget-contacts__item widget-contacts__item--adress"><?php echo esc_html($address); ?></div>
            <?php endif; ?>
            
            <?php if ($phone) : ?>
                <div class="widget-contacts__item widget-contacts__item--tel"><?php echo esc_html($phone); ?></div>
            <?php endif; ?>
            
            <?php if ($email) : ?>
                <div class="widget-contacts__item widget-contacts__item--mail">
                    <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>
                </div>
            <?php endif; ?>
        </div>
<?php
    }

    public function form($instance)
    {
        $title = !empty($instance['title']) ? $instance['title'] : 'Контакты редакции';
?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Заголовок:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p style="color: #666; font-size: 12px;">
            Контактные данные редактируются в <a href="<?php echo admin_url('admin.php?page=khag-theme-settings'); ?>">общих настройках темы</a>
        </p>
<?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        return $instance;
    }
}