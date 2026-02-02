<?php

/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package khag
 */

get_header();
?>

<main class="main">

	<section class="page-header">
		<div class="container">
			<h1 class="page-header__title">404 - Страница не найдена</h1>
		</div>
	</section>

	<section class="info-section section-margins">
		<div class="container">
			<p>К сожалению, запрашиваемая страница не существует. Возможно, она была удалена или перемещена.</p>
			<br>
			<p>Попробуйте воспользоваться поиском или вернуться на <a href="<?php echo esc_url(home_url('/')); ?>" class="link">главную страницу</a>.</p>

			<div class="search-form-wrapper">
				<form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
					<input type="search" name="s" placeholder="Попробуйте другой запрос..." required>
					<button type="submit" class="search-form-wrapper__btn btn">Искать снова</button>
				</form>
			</div>
			
		</div>
	</section>

</main>

<?php
get_footer();
