<?php
get_header();
?>

<main>
	<section class="page-header">
		<div class="container">

			<h1 class="page-header__title"><?php the_title(); ?></h1>

			<?php khag_get_breadcrumbs(); ?>

			<div class="page-header__descr">
				<?php
				$current_post_type = get_queried_object()->name;
				$options_page_id = $current_post_type . '-archive-options';

				echo get_field('archive-news-descr', $options_page_id);
				?>
			</div>

		</div>
	</section>

	<section class="info-section" style="margin-bottom: 40px;">
		<div class="container">

			<div class="info-section__wrapper">

				<article class="info-section__content">
					<?php the_content(); ?>
				</article>

				<div class="info-section__sidebar">
					<?php dynamic_sidebar('sidebar-single'); ?>
				</div>

			</div>

		</div>
	</section>
	
</main>

<?php
get_footer();
