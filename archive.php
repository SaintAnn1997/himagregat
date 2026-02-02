<?php

/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package khag
 */

get_header();
?>

<main>

	<section class="page-header">
		<div class="container">

			<h1 class="page-header__title"><?php post_type_archive_title(); ?></h1>

			<?php khag_get_breadcrumbs(); ?>

			<div class="page-header__descr">
				<?php
				$current_post_type = get_queried_object()->name;
				$description = get_option($current_post_type . '_archive_description');

				if ($description) {
					echo '<p>' . esc_html($description) . '</p>';
				}
				?>
			</div>

		</div>
	</section>

	<section class="archive-section archive-section--alt-tags archive-section--three-cols section-margins">
		<div class="container">

			<div class="archive-section__cards">

				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

						<div class="archive-section__card">
							<div class="archive-section__card-header">
								<?php if (has_post_thumbnail()) : ?>
									<?php
									$thumbnail_id = get_post_thumbnail_id();
									$alt_text = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
									$alt_text = $alt_text ? $alt_text : get_the_title();
									?>
									<img class="archive-section__card-img"
										src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'large'); ?>"
										alt="<?php echo esc_attr($alt_text); ?>">
								<?php endif; ?>
								<?php

								$current_post_type = get_post_type();
								$taxonomies = get_object_taxonomies($current_post_type, 'objects');
								$has_term = false;
								$term_name = '';

								foreach ($taxonomies as $taxonomy) {
									if ($taxonomy->hierarchical) {
										$post_terms = get_the_terms(get_the_ID(), $taxonomy->name);
										if ($post_terms && !is_wp_error($post_terms)) {
											$term = array_shift($post_terms);
											$term_name = esc_html($term->name);
											$has_term = true;
											break;
										}
									}
								}

								if ($has_term) {
									echo '<span class="archive-section__card-tag tag">' . $term_name . '</span>';
								}
								?>
							</div>

							<div class="archive-section__card-bottom">
								<div class="archive-section__card-date"><?php echo get_the_date('j F Y'); ?></div>
								<div class="archive-section__card-title"><?php the_title(); ?></div>

								<div class="archive-section__card-descr"><?php echo esc_html(khag_get_trimmed_excerpt(220)); ?></div>

								<a href="<?php the_permalink(); ?>" class="archive-section__card-more btn" target="_blank">Подробнее</a>
							</div>

						</div>

				<?php endwhile;
				endif; ?>

			</div>

			<?php
			$paged = get_query_var('paged') ? (int)get_query_var('paged') : 1;
			$per_page = get_query_var('posts_per_page') ? (int)get_query_var('posts_per_page') : get_option('posts_per_page');

			$total_posts = $wp_query->found_posts;
			$total_pages = ceil($total_posts / $per_page);

			if ($total_pages > 1) : ?>
				<nav class="pagination" aria-label="Постраничная навигация по архиву">
					<ul class="pagination__items">
						<?php if ($paged > 1) : ?>
							<li class="pagination__item pagination__prev">
								<a href="<?php echo esc_url(get_pagenum_link($paged - 1)); ?>">
									<svg width="7" height="13" viewBox="0 0 7 13" fill="none"
										xmlns="http://www.w3.org/2000/svg">
										<path d="M6.5 12.5L0.5 6.5L6.5 0.5" stroke="#009652" stroke-linecap="round"
											stroke-linejoin="round" />
									</svg>
								</a>
							</li>
						<?php endif; ?>

						<?php
					$range = 2;
					$start = max(1, $paged - $range);
					$end = min($total_pages, $paged + $range);

					for ($i = $start; $i <= $end; $i++) {
						$active_class = ($i == $paged) ? ' pagination__item--current' : '';
						echo '<li class="pagination__item' . $active_class . '"><a href="' . esc_url(get_pagenum_link($i)) . '">' . $i . '</a></li>';
					}
					?>
					<?php if ($paged < $total_pages) : ?>
							<li class="pagination__item pagination__next">
								<a href="<?php echo esc_url(get_pagenum_link($paged + 1)); ?>">
									<svg width="7" height="13" viewBox="0 0 7 13" fill="none"
										xmlns="http://www.w3.org/2000/svg">
										<path d="M0.5 12.5L6.5 6.5L0.5 0.5" stroke="#009652" stroke-linecap="round"
											stroke-linejoin="round" />
									</svg>
								</a>
							</li>
						<?php endif; ?>
					</ul>
				</nav>
			<?php endif; ?>

		</div>
	</section>

	<?php get_template_part('template-parts/subscribe-block'); ?>

</main>

<?php

get_footer();
