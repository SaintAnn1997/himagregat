<?php

/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package khag
 */

get_header();
?>

<main>
	<section class="page-header">
		<div class="container">
			<?php if (have_posts()) : ?>
				<h1 class="page-header__title">
					Результаты поиска: <span style="color: #009652;"><?php echo get_search_query(); ?></span>
				</h1>
				<p class="page-header__descr">Найдено результатов: <?php echo $wp_query->found_posts; ?></p>
			<?php else : ?>
				<h1 class="page-header__title">Ничего не найдено</h1>
				<p class="page-header__descr">К сожалению, по вашему запросу "<strong><?php echo get_search_query(); ?></strong>" ничего не найдено</p>
			<?php endif; ?>
		</div>
	</section>

	<?php if (have_posts()) : ?>
		<section class="search-results-section">
			<div class="container">
				<div class="search-results-list">
					<?php while (have_posts()) : the_post(); ?>
						<?php get_template_part('template-parts/content', 'search'); ?>
					<?php endwhile; ?>
				</div>

				<?php
				// Пагинация для результатов поиска
				if ($wp_query->max_num_pages > 1) :
					$paged = max(1, get_query_var('paged'));
				?>
					<nav class="pagination" aria-label="Постраничная навигация">
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
							$total_pages = $wp_query->max_num_pages;
							$start = max(1, $paged - $range);
							$end = min($total_pages, $paged + $range);

							for ($i = $start; $i <= $end; $i++) {
								$active_class = ($i == $paged) ? ' pagination__item--current' : '';
								echo '<li class="pagination__item' . $active_class . '"><a href="' . esc_url(get_pagenum_link($i)) . '">' . $i . '</a></li>';
							}
							?> <?php if ($paged < $wp_query->max_num_pages) : ?>
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

	<?php else : ?>
		<section class="info-section">
			<div class="container">
				<div class="search-no-results">
					<p>Попробуйте:</p>
					<ul>
						<li>Использовать более общие ключевые слова</li>
						<li>Проверить правильность написания слов</li>
						<li>Использовать синонимы или альтернативные формулировки</li>
					</ul>

					<div class="search-form-wrapper">
						<form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
							<input type="search" name="s" placeholder="Попробуйте другой запрос..." required>
							<button type="submit" class="search-form-wrapper__btn btn">Искать снова</button>
						</form>
					</div>
				</div>
			</div>
		</section>
	<?php endif; ?>

</main>

<?php
get_footer();
