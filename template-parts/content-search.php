<?php

/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package khag
 */

$post_type = get_post_type();
$post_type_obj = get_post_type_object($post_type);
$post_type_name = $post_type_obj ? $post_type_obj->labels->singular_name : 'Запись';

$post_url = get_permalink();

if ($post_type === 'tribe_events') {
	$post_date = tribe_get_start_date(get_the_ID(), false, 'd.m.Y');
} else {
	$post_date = get_the_date('d.m.Y');
}
?>

<div class="search-result-item">
	<div class="search-result-item__header">
		<span class="search-result-item__type"><?php echo esc_html($post_type_name); ?></span>
		<?php if ($post_date) : ?>
			<span class="search-result-item__date"><?php echo esc_html($post_date); ?></span>
		<?php endif; ?>
	</div>

	<h3 class="search-result-item__title">
		<a href="<?php echo esc_url($post_url); ?>">
			<?php echo esc_html(get_the_title()); ?>
		</a>
	</h3>

	<?php if ($post_type !== 'magazine') : ?>
		<div class="search-result-item__excerpt">
			<?php
			esc_html(the_excerpt());
			?>
		</div>
	<?php endif; ?>

	<a href="<?php echo esc_url($post_url); ?>" class="search-result-item__link">
		Подробнее
	</a>
</div>