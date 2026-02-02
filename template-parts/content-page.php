<?php

/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package khag
 */

?>

<section class="page-header">
	<div class="container">

		<h1 class="page-header__title"><?php the_title(); ?></h1>

		<?php khag_get_breadcrumbs(); ?>

	</div>
</section>

<section class="info-section">
	<div class="container">

		<div class="info-section__content">
			<?php the_content(); ?>
		</div>

	</div>
</section>