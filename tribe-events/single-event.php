<?php

/**
 * Шаблон одиночного события
 * Кастомный шаблон для отображения страницы события
 *
 * @package khag
 * @version 1.0.0
 */

if (! defined('ABSPATH')) {
	die('-1');
}

$event_id = get_queried_object_id();

?>

<main>
	<section class="page-header">
		<div class="container">

			<?php do_action('tribe_events_single_event_before_the_title'); ?>

			<h1 class="page-header__title"><?php echo esc_html(get_the_title($event_id)); ?></h1>

			<?php do_action('tribe_events_single_event_after_the_title'); ?>

		</div>
	</section>

	<section class="info-section">
		<div class="container">

			<div class="info-section__wrapper">

				<article class="info-section__content">

					<?php
					do_action('tribe_events_single_event_before_the_meta');
					?>

					<?php
					do_action('tribe_events_single_event_before_the_content');
					the_content();
					do_action('tribe_events_single_event_after_the_content');
					?>

					<?php
					do_action('tribe_events_single_event_after_the_meta');
					?>

					<?php
					// Получаем данные организатора
					$organizer_ids = tribe_get_organizer_ids($event_id);
					if (!empty($organizer_ids)) :
						$organizer_id = $organizer_ids[0]; // Берём первого организатора
						$organizer_name = tribe_get_organizer($organizer_id);
						$organizer_phone = tribe_get_organizer_phone($organizer_id);
						$organizer_email = tribe_get_organizer_email($organizer_id);
						$organizer_website = tribe_get_organizer_website_url($organizer_id);

						if ($organizer_name || $organizer_phone || $organizer_email) :
					?>
							<p class="event-info-block__item">
								<strong>Оргкомитет мероприятия:</strong>
							</p>

							<?php if ($organizer_email) : ?>
								<p class="event-info-block__item">
									<a href="mailto:<?php echo esc_attr($organizer_email); ?>" class="event-info-block__link">
										<?php echo esc_html($organizer_email); ?>
									</a>
								</p>
							<?php endif; ?>

							<?php if ($organizer_phone) : ?>
								<p class="event-info-block__item">
									<?php echo esc_html($organizer_phone); ?>
								</p>
							<?php endif; ?>
					<?php
						endif;
					endif;
					?>

					<?php
					$start_date = tribe_get_start_date($event_id, false, 'j F Y');
					$end_date = tribe_get_end_date($event_id, false, 'j F Y');

					$venue_id = tribe_get_venue_id($event_id);
					$venue_name = tribe_get_venue($event_id);
					$city = tribe_get_city($event_id);
					$venue_address = tribe_get_address($event_id);
					$cost  = tribe_get_formatted_cost($event_id);

					$event_website = tribe_get_event_website_url($event_id);

					$archive_url = home_url('/events/');
					?>

					<div class="event-info-block">

						<?php if ($start_date) : ?>
							<p class="event-info-block__item">
								<strong>Дата проведения:</strong>
								<?php
								if ($start_date === $end_date) {
									echo esc_html($start_date . ' г.');
								} else {
									echo esc_html($start_date . ' – ' . $end_date . ' г.');
								}
								?>
							</p>
						<?php endif; ?>

						<?php if ($cost) : ?>
							<p class="event-info-block__item">
								<strong>Цена билета:</strong>
								<?php echo esc_html($cost); ?>
							</p>
						<?php endif; ?>

						<?php if ($city || $venue_address) : ?>
							<p class="event-info-block__item">
								<strong>Адрес:</strong>
								<?php
								$address_text = '';
								if ($city && $venue_address) {
									$address_text = $city . ', ' . $venue_address;
								} elseif ($city) {
									$address_text = $city;
								} else {
									$address_text = $venue_address;
								}

								$venue_website = tribe_get_venue_website_url($event_id);

								if ($venue_website) {
									echo '<a href="' . esc_url($venue_website) . '" target="_blank" rel="noopener" class="event-info-block__link">' . esc_html($address_text) . '</a>';
								} elseif (function_exists('tribe_show_google_map_link') && tribe_show_google_map_link($event_id)) {
									$map_link = tribe_get_map_link($event_id);
									if ($map_link) {
										echo '<a href="' . esc_url($map_link) . '" target="_blank" rel="noopener" class="event-info-block__link">' . esc_html($address_text) . '</a>';
									} else {
										echo esc_html($address_text);
									}
								} else {
									echo esc_html($address_text);
								}
								?>
							</p>
						<?php endif; ?>

						<?php if ($event_website) : ?>
							<p class="event-info-block__item">
								<strong>Ссылка на сайт:</strong>
								<a href="<?php echo esc_url($event_website); ?>" target="_blank" rel="noopener" class="event-info-block__link">
									<?php echo esc_html($event_website); ?>
								</a>
							</p>
						<?php endif; ?>

						<p class="event-info-block__item">
							<a href="<?php echo esc_url($archive_url); ?>" class="event-info-block__back-link">
								Возврат к списку
							</a>
						</p>
					</div>

				</article>

				<div class="info-section__sidebar">
					<?php dynamic_sidebar('sidebar-single'); ?>
				</div>

			</div>

		</div>
	</section>

	<?php
	do_action('tribe_events_after_footer');
	?>

</main>

<?php
get_footer();
