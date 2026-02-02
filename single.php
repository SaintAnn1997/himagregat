<?php

/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package khag
 */

get_header();
?>

<main>
	<section class="page-header">
		<div class="container">
			<h1 class="page-header__title page-header__title--single"><?php the_title(); ?></h1>
			<?php khag_get_breadcrumbs(); ?>
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
