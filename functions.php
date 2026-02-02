<?php

/**
 * khag functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package khag
 */

if (! defined('_S_VERSION')) {
	// Replace the version number of the theme on each release.
	define('_S_VERSION', '1.0.0');
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function khag_setup()
{
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on khag, use a find and replace
		* to change 'khag' to the name of your theme in all the template files.
		*/
	load_theme_textdomain('khag', get_template_directory() . '/languages');

	// Add default posts and comments RSS feed links to head.
	add_theme_support('automatic-feed-links');

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support('title-tag');

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support('post-thumbnails');

	// Регистрация местоположений меню
	register_nav_menus(
		array(
			'header-menu' => esc_html__('Меню в шапке', 'khag'),
			'footer-menu-1' => esc_html__('Меню в подвале "Разделы сайта"', 'khag'),
			'footer-menu-2' => esc_html__('Меню в подвале "Журнал"', 'khag'),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'khag_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support('customize-selective-refresh-widgets');

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action('after_setup_theme', 'khag_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function khag_content_width()
{
	$GLOBALS['content_width'] = apply_filters('khag_content_width', 640);
}
add_action('after_setup_theme', 'khag_content_width', 0);

/**
 * Enqueue scripts and styles.
 */

function khag_scripts()
{
	wp_enqueue_style('khag-style', get_stylesheet_uri(), array(), _S_VERSION);

	// Основные стили
	// wp_enqueue_style('khag-main-style', get_template_directory_uri() . '/assets/css/style.css', array(), filemtime(get_template_directory() . '/assets/css/style.css'));

	// Основной JavaScript 
	// wp_enqueue_script('khag-main-scripts', get_template_directory_uri() . '/assets/js/main.js', array(), filemtime(get_template_directory() . '/assets/js/main.js'), true);

	$manifest_path = get_template_directory() . '/.vite/manifest.json';
	if (!file_exists($manifest_path)) return;

	$manifest = json_decode(file_get_contents($manifest_path), true);

	// Подключаем основной JS (js/main.js)
	if (!empty($manifest['js/main.js']['file'])) {
		wp_enqueue_script(
			'khag-main-js',
			get_template_directory_uri() . '/' . $manifest['js/main.js']['file'],
			array(),
			null,
			true
		);
	}

	// Подключаем основной CSS (sass/style.scss)
	if (!empty($manifest['sass/style.scss']['file'])) {
		wp_enqueue_style(
			'khag-main-css',
			get_template_directory_uri() . '/' . $manifest['sass/style.scss']['file'],
			array(),
			null
		);
	}

	// Локализация для AJAX скриптов в main.js
	wp_localize_script('khag-main-js', 'ajax_object', [
		'ajaxurl' => admin_url('admin-ajax.php'),
		'nonce' => wp_create_nonce('khag_load_news_nonce')
	]);

	// Скрипт для комментариев
	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}
}

add_action('wp_enqueue_scripts', 'khag_scripts');

// Подключение шрифтов 
add_action('wp_head', function () {
?>
	<link rel="preload" href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
	<noscript>
		<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap">
	</noscript>
<?php
}, 1);

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

// Хлебные крошки
require get_template_directory() . '/inc/widgets/breadcrumbs.php';

// Регистрация виджетов
require get_template_directory() . '/inc/widgets/widget-subscribe.php';
require get_template_directory() . '/inc/widgets/widget-events.php';
require get_template_directory() . '/inc/widgets/widget-contacts.php';
require get_template_directory() . '/inc/widgets/widget-catalog.php';
require get_template_directory() . '/inc/widgets/widget-journal-notice.php';
require get_template_directory() . '/inc/widgets/widgets.php';

// The events calendar
require get_template_directory() . '/inc/events-calendar.php';

// Скрытие версии WP
add_filter('the_generator', '__return_null');

function  khag_remove_ver_css_js($src, $handle)
{
	if (strpos($src, 'ver='))
		$src = remove_query_arg('ver', $src);
	return $src;
}
add_filter('style_loader_src', 'khag_remove_ver_css_js', 9999, 2);
add_filter('script_loader_src', 'khag_remove_ver_css_js', 9999, 2);

// Изменение собщения при неверном вводе логина или пароля
add_filter('login_errors', function () {
	return 'Введен неверный логин или пароль';
});

// Исключение возможности авторизации через страницу wp-login.php

// function khag_wp_login_filter($url, $path, $orig_scheme) {
// 	$old = array(('/(wp-login\.php)/'));
// 	$new = array('new-login');
// 	return preg_replace($old, $new, $url, 1);
// }

// add_filter('site_url', 'khag_wp_login_filter', 10, 3);

// function khag_wp_login_redirect() {
// 	if (strpos($_SERVER['REQUEST_URI'], 'new-login') === false) {
// 		wp_redirect(site_url());
// 		exit();
// 	}
// }

// add_action( 'login_init', 'khag_wp_login_redirect');

// Удалить ссылки на REST API из <head>
remove_action('wp_head', 'rest_output_link_wp_head');
remove_action('wp_head', 'wp_oembed_add_discovery_links');
remove_action('template_redirect', 'rest_output_link_header', 11);

add_filter('get_custom_logo', function ($html) {
	return str_replace('custom-logo-link', 'custom-logo-link header__logo', $html);
});

function khag_register_post_types()
{

	register_post_type('news', [
		'labels' => [
			'name'               => 'Новости',
			'singular_name'      => 'Новость',
			'add_new'            => 'Добавить новость',
			'add_new_item'       => 'Добавление новости',
			'edit_item'          => 'Редактирование новости',
			'not_found'          => 'Не найдено',
			'not_found_in_trash' => 'Не найдено в корзине',
			'menu_name'          => 'Новости',
		],
		'public'             => true,
		'menu_icon'          => 'dashicons-megaphone',
		'hierarchical'       => false,
		'supports'           => ['title', 'editor', 'excerpt', 'author', 'revisions', 'thumbnail'],
		'has_archive'        => true,
		'rewrite'            => [
			'slug' => 'news'
		],
		'show_in_rest'       => true,
		'query_var'          => true,
	]);

	register_post_type('technic', [
		'labels' => [
			'name'               => 'Техника и технологии',
			'singular_name'      => 'Техника и технологии',
			'add_new'            => 'Добавить статью',
			'add_new_item'       => 'Добавление статьи',
			'edit_item'          => 'Редактирование статьи',
			'not_found'          => 'Не найдено',
			'not_found_in_trash' => 'Не найдено в корзине',
			'menu_name'          => 'Техника и технологии',
		],
		'public'             => true,
		'menu_icon'          => 'dashicons-admin-tools',
		'hierarchical'       => false,
		'supports'           => ['title', 'editor', 'excerpt', 'author', 'revisions', 'thumbnail'],
		'has_archive'        => true,
		'rewrite'            => [
			'slug' => 'technic'
		],
		'show_in_rest'       => true,
		'query_var'          => true,
	]);

	register_post_type('analytics', [
		'labels' => [
			'name'               => 'Аналитика и мнения',
			'singular_name'      => 'Аналитика и мнения',
			'add_new'            => 'Добавить статью',
			'add_new_item'       => 'Добавление статьи',
			'edit_item'          => 'Редактирование статьи',
			'not_found'          => 'Не найдено',
			'not_found_in_trash' => 'Не найдено в корзине',
			'menu_name'          => 'Аналитика и мнения',
		],
		'public'             => true,
		'menu_icon'          => 'dashicons-chart-line',
		'hierarchical'       => false,
		'supports'           => ['title', 'editor', 'excerpt', 'author', 'revisions', 'thumbnail'],
		'has_archive'        => true,
		'rewrite'            => [
			'slug' => 'analytics'
		],
		'show_in_rest'       => true,
		'query_var'          => true,
	]);

	register_post_type('experience', [
		'labels' => [
			'name'               => 'Опыт',
			'singular_name'      => 'Опыт',
			'add_new'            => 'Добавить статью',
			'add_new_item'       => 'Добавление статьи',
			'edit_item'          => 'Редактирование статьи',
			'not_found'          => 'Не найдено',
			'not_found_in_trash' => 'Не найдено в корзине',
			'menu_name'          => 'Опыт',
		],
		'public'             => true,
		'menu_icon'          => 'dashicons-awards',
		'hierarchical'       => false,
		'supports'           => ['title', 'editor', 'excerpt', 'author', 'revisions', 'thumbnail'],
		'has_archive'        => true,
		'rewrite'            => [
			'slug' => 'experience'
		],
		'show_in_rest'       => true,
		'query_var'          => true,
	]);

	register_post_type('state-regulation', [
		'labels' => [
			'name'               => 'Госрегулирование',
			'singular_name'      => 'Госрегулирование',
			'add_new'            => 'Добавить статью',
			'add_new_item'       => 'Добавление статьи',
			'edit_item'          => 'Редактирование статьи',
			'not_found'          => 'Не найдено',
			'not_found_in_trash' => 'Не найдено в корзине',
			'menu_name'          => 'Госрегулирование',
		],
		'public'             => true,
		'menu_icon'          => 'dashicons-shield-alt',
		'hierarchical'       => false,
		'supports'           => ['title', 'editor', 'excerpt', 'author', 'revisions', 'thumbnail'],
		'has_archive'        => true,
		'rewrite'            => [
			'slug' => 'state-regulation'
		],
		'show_in_rest'       => true,
		'query_var'          => true,
	]);

	register_post_type('magazine', [
		'labels' => [
			'name'               => 'Архив журнала',
			'singular_name'      => 'Архив журнала',
			'add_new'            => 'Добавить журнал',
			'add_new_item'       => 'Добавление журнала',
			'edit_item'          => 'Редактирование журнала',
			'not_found'          => 'Не найдено',
			'not_found_in_trash' => 'Не найдено в корзине',
			'menu_name'          => 'Архив журнала',
		],
		'public'             => true,
		'menu_icon'          => 'dashicons-book',
		'hierarchical'       => false,
		'supports'           => ['title', 'editor', 'excerpt', 'author', 'revisions', 'thumbnail'],
		'has_archive'        => false,
		'rewrite'            => [
			'slug' => 'magazine'
		],
		'show_in_rest'       => true,
		'query_var'          => true,
	]);
}

add_action('init', 'khag_register_post_types');

// Регистрация таксономий
function khag_register_taxonomies()
{

	register_taxonomy(
		'news_category',
		'news',
		[
			'labels' => [
				'name'              => 'Категории новостей',
				'singular_name'     => 'Категория новостей',
				'search_items'      => 'Поиск категорий',
				'all_items'         => 'Все категории',
				'edit_item'         => 'Редактировать категорию',
				'update_item'       => 'Обновить категорию',
				'add_new_item'      => 'Добавить новую категорию',
				'new_item_name'     => 'Название новой категории',
				'menu_name'         => 'Категории новостей',
			],
			'public'            => true,
			'hierarchical'      => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'            => [
				'slug' => 'news-category',
				'with_front' => false
			],
			'meta_box_cb'       => 'post_categories_meta_box',
		]
	);

	register_taxonomy(
		'technic_category',
		'technic',
		[
			'labels' => [
				'name'              => 'Категории "Техника и технологии"',
				'singular_name'     => 'Категория "Техника и технологии"',
				'search_items'      => 'Поиск категорий',
				'all_items'         => 'Все категории',
				'edit_item'         => 'Редактировать категорию',
				'update_item'       => 'Обновить категорию',
				'add_new_item'      => 'Добавить новую категорию',
				'new_item_name'     => 'Название новой категории',
				'menu_name'         => 'Категории',
			],
			'public'            => true,
			'hierarchical'      => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'            => [
				'slug' => 'technic-category',
				'with_front' => false
			],
			'meta_box_cb'       => 'post_categories_meta_box',
		]
	);

	register_taxonomy(
		'analytics_category',
		'analytics',
		[
			'labels' => [
				'name'              => 'Категории "Аналитика и мнение"',
				'singular_name'     => 'Категория "Аналитика и мнение"',
				'search_items'      => 'Поиск категорий',
				'all_items'         => 'Все категории',
				'edit_item'         => 'Редактировать категорию',
				'update_item'       => 'Обновить категорию',
				'add_new_item'      => 'Добавить новую категорию',
				'new_item_name'     => 'Название новой категории',
				'menu_name'         => 'Категории',
			],
			'public'            => true,
			'hierarchical'      => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'            => [
				'slug' => 'analytics-category',
				'with_front' => false
			],
			'meta_box_cb'       => 'post_categories_meta_box',
		]
	);

	register_taxonomy(
		'experience_category',
		'experience',
		[
			'labels' => [
				'name'              => 'Категории "Опыт"',
				'singular_name'     => 'Категория "Опыт"',
				'search_items'      => 'Поиск категорий',
				'all_items'         => 'Все категории',
				'edit_item'         => 'Редактировать категорию',
				'update_item'       => 'Обновить категорию',
				'add_new_item'      => 'Добавить новую категорию',
				'new_item_name'     => 'Название новой категории',
				'menu_name'         => 'Категории',
			],
			'public'            => true,
			'hierarchical'      => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'            => [
				'slug' => 'experience-category',
				'with_front' => false
			],
			'meta_box_cb'       => 'post_categories_meta_box',
		]
	);

	register_taxonomy(
		'regulation_category',
		'state-regulation',
		[
			'labels' => [
				'name'              => 'Категории "Госрегулирование"',
				'singular_name'     => 'Категория "Госрегулирование"',
				'search_items'      => 'Поиск категорий',
				'all_items'         => 'Все категории',
				'edit_item'         => 'Редактировать категорию',
				'update_item'       => 'Обновить категорию',
				'add_new_item'      => 'Добавить новую категорию',
				'new_item_name'     => 'Название новой категории',
				'menu_name'         => 'Категории',
			],
			'public'            => true,
			'hierarchical'      => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'            => [
				'slug' => 'regulation-category',
				'with_front' => false
			],
			'meta_box_cb'       => 'post_categories_meta_box',
		]
	);
}

add_action('init', 'khag_register_taxonomies');

// Добавление подменю "Описание" для каждого типа записей
function khag_add_archive_settings_pages()
{
	$post_types = [
		'news' => 'Новости',
		'technic' => 'Техника и технологии',
		'analytics' => 'Аналитика и мнение',
		'experience' => 'Опыт',
		'state-regulation' => 'Госрегулирование',
		'tribe_events' => 'Выставки',
	];

	foreach ($post_types as $post_type => $label) {
		add_submenu_page(
			'edit.php?post_type=' . $post_type,
			'Описание',
			'Описание',
			'manage_options',
			$post_type . '-archive-settings',
			'khag_render_archive_settings_page'
		);
	}
}
add_action('admin_menu', 'khag_add_archive_settings_pages');

// Отрисовка страницы настроек архива
function khag_render_archive_settings_page()
{
	// Получаем текущий тип записи из URL
	// $current_screen = get_current_screen();
	$post_type = str_replace('-archive-settings', '', $_GET['page']);

	// Сохранение данных
	if (isset($_POST['khag_archive_description_submit'])) {
		check_admin_referer('khag_archive_settings_' . $post_type);

		$description = sanitize_textarea_field($_POST['archive_description']);
		update_option($post_type . '_archive_description', $description);

		echo '<div class="notice notice-success is-dismissible"><p>Настройки сохранены!</p></div>';
	}

	// Получение сохраненного описания
	$description = get_option($post_type . '_archive_description', '');

	// Названия типов записей
	$post_type_labels = [
		'news' => 'новостей',
		'technic' => 'статей "Техника и технологии"',
		'analytics' => 'статей "Аналитика и мнение"',
		'experience' => 'статей "Опыт"',
		'state-regulation' => 'статей "Госрегулирование"',
		'tribe_events' => 'выставки',
	];

	$label = isset($post_type_labels[$post_type]) ? $post_type_labels[$post_type] : '';
?>
	<div class="wrap">
		<h1>Настройки архива <?php echo esc_html($label); ?></h1>

		<form method="post" action="">
			<?php wp_nonce_field('khag_archive_settings_' . $post_type); ?>

			<table class="form-table">
				<tr>
					<th scope="row">
						<label for="archive_description">Описание архива</label>
					</th>
					<td>
						<textarea
							id="archive_description"
							name="archive_description"
							rows="5"
							class="large-text"
							placeholder="Введите описание для страницы архива..."><?php echo esc_textarea($description); ?></textarea>
						<p class="description">
							Это описание будет отображаться на странице архива всех записей этого типа.
						</p>
					</td>
				</tr>
			</table>

			<?php submit_button('Сохранить настройки', 'primary', 'khag_archive_description_submit'); ?>
		</form>
	</div>
<?php
}

// AJAX обработчик для загрузки новостей по категориям
function khag_load_news_by_category()
{
	if (!wp_verify_nonce($_POST['nonce'], 'khag_load_news_nonce')) {
		wp_die('Ошибка проверки безопасности');
	}

	$category_id = sanitize_text_field($_POST['category_id']);
	$paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;
	$posts_per_page = isset($_POST['posts_per_page']) ? intval($_POST['posts_per_page']) : 10;

	// Отладка
	error_log('AJAX khag_load_news_by_category: category_id=' . $category_id . ', paged=' . $paged . ', posts_per_page=' . $posts_per_page);

	ob_start();
	get_template_part('template-parts/archive-news', null, [
		'category_id' => $category_id,
		'paged' => $paged,
		'posts_per_page' => $posts_per_page
	]);
	echo ob_get_clean();
	wp_die();
}

add_action('wp_ajax_khag_load_news_by_category', 'khag_load_news_by_category');
add_action('wp_ajax_nopriv_khag_load_news_by_category', 'khag_load_news_by_category');

// AJAX обработчик для загрузки журналов
function khag_load_magazines()
{
	if (!wp_verify_nonce($_POST['nonce'], 'khag_load_news_nonce')) {
		wp_die('Ошибка проверки безопасности');
	}

	$paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;
	$posts_per_page = isset($_POST['posts_per_page']) ? intval($_POST['posts_per_page']) : 11;

	ob_start();
	get_template_part('template-parts/archive-magazine-ajax', null, [
		'paged' => $paged,
		'posts_per_page' => $posts_per_page
	]);
	echo ob_get_clean();
	wp_die();
}

add_action('wp_ajax_khag_load_magazines', 'khag_load_magazines');
add_action('wp_ajax_nopriv_khag_load_magazines', 'khag_load_magazines');


// Настройка Relevanssi для поиска по всем типам постов
function khag_relevanssi_post_types($post_types)
{
	// Добавляем все публичные типы постов в индекс Relevanssi
	$all_post_types = get_post_types(['public' => true], 'names');
	return $all_post_types;
}
add_filter('relevanssi_index_post_types', 'khag_relevanssi_post_types');

// Устанавливаем количество результатов поиска на странице
function khag_search_posts_per_page($query)
{
	if (!is_admin() && $query->is_search && $query->is_main_query()) {
		$query->set('posts_per_page', 10);
	}
}
add_action('pre_get_posts', 'khag_search_posts_per_page');

// Добавляем таксономии в индекс Relevanssi
function khag_relevanssi_taxonomies($taxonomies)
{
	// Получаем все публичные таксономии
	$all_taxonomies = get_taxonomies(['public' => true], 'names');
	return $all_taxonomies;
}
add_filter('relevanssi_index_taxonomies', 'khag_relevanssi_taxonomies');

// Включаем поиск по таксономиям
add_filter('relevanssi_index_taxonomy_terms', '__return_true');

// Добавляем кастомный контент в индекс Relevanssi для страницы "О журнале"
function khag_relevanssi_custom_content($content, $post)
{
	// Если это страница "О журнале" (по шаблону)
	if (get_page_template_slug($post->ID) === 'page-about.php') {
		// Добавляем контент всех табов в индекс
		$custom_content = '
			О журнале
			Архив журнала
			Подписка на журнал
			Реклама в журнале
			Ежемесячный журнал
			Химическая промышленность
			Нефтехимия
			Аналитические материалы
			Новости отрасли
		';
		$content .= ' ' . $custom_content;
	}

	// Добавляем ключевые слова для кастомных типов постов
	$post_type = get_post_type($post);

	if ($post_type === 'news') {
		$content .= ' новости новость';
	} elseif ($post_type === 'technic') {
		$content .= ' техника технологии';
	} elseif ($post_type === 'analytics') {
		$content .= ' аналитика мнение статья';
	} elseif ($post_type === 'experience') {
		$content .= ' опыт статья';
	} elseif ($post_type === 'state-regulation') {
		$content .= ' госрегулирование статья';
	} elseif ($post_type === 'tribe_events') {
		$content .= ' выставка событие мероприятие';
	}

	return $content;
}
add_filter('relevanssi_content_to_index', 'khag_relevanssi_custom_content', 10, 2);

// Регистрация страницы настроек темы
acf_add_options_page([
	'page_title' => 'Настройки темы',
	'menu_title' => 'Настройки темы',
	'menu_slug'  => 'khag-theme-settings',
	'capability' => 'edit_theme_options',
	'icon_url' => 'dashicons-admin-generic',
	'position'   => 9.1,
	'autoload'   => true
]);

function khag_get_latest_magazine()
{
	static $latest_magazine = null;

	if ($latest_magazine === null) {
		$magazines = get_posts(array(
			'post_type' => 'magazine',
			'posts_per_page' => 1,
			'post_status' => 'publish',
			'orderby' => 'date',
			'order' => 'DESC'
		));

		if (!empty($magazines)) {
			$magazine = $magazines[0];
			$latest_magazine = array(
				'id' => $magazine->ID,
				'title' => get_the_title($magazine->ID),
				'date' => get_the_date('F Y', $magazine->ID),
				'link' => get_field('journal_pdf_file', $magazine->ID),
				'text' => sprintf('Вышел новый выпуск журнала "%s" - за %s', get_the_title($magazine->ID), get_the_date('F Y', $magazine->ID))
			);
		}
	}

	return $latest_magazine;
}

function khag_journal_notice($classes = '')
{
	$magazine = khag_get_latest_magazine();

	if (!$magazine) {
		return;
	}

	$classes = $classes ? ' ' . esc_attr($classes) : '';
?>
	<div class="journal-notice<?php echo $classes; ?>">
		<div class="journal-notice__text">
			<?php echo esc_html($magazine['text']); ?>
		</div>
		<a href="<?php echo esc_url($magazine['link']); ?>" target="_blank" rel="noopener noreferrer" class="journal-notice__btn btn">Читать журнал</a>
	</div>
<?php
}

// Helper function: обрезка текста с поддержкой UTF-8
function khag_truncate_text($text, $length = 160, $ending = '...')
{
	// Очищаем от HTML и декодируем entities
	$text = wp_strip_all_tags($text, true);
	$text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
	$text = trim($text);

	// Проверяем длину и обрезаем
	if (mb_strlen($text, 'UTF-8') > $length) {
		$text = mb_substr($text, 0, $length, 'UTF-8');
		$text = rtrim($text);
		$text .= $ending;
	}

	return $text;
}

// Helper function: получить обрезанный заголовок
function khag_get_trimmed_title($length = 160, $post_id = null)
{
	$title = $post_id ? get_the_title($post_id) : get_the_title();
	return khag_truncate_text($title, $length);
}

// Helper function: получить обрезанный excerpt
function khag_get_trimmed_excerpt($length = 320, $post_id = null)
{
	global $post;

	if ($post_id) {
		$current_post = get_post($post_id);
	} else {
		$current_post = $post;
	}

	if (!$current_post) {
		return '';
	}

	if (!empty($current_post->post_excerpt)) {
		$text = $current_post->post_excerpt;
	} else {
		$text = $current_post->post_content;
	}

	return wp_html_excerpt($text, $length, '...');
}

// Регистрируем тип записей "Подписчики"
add_action('init', function () {
	register_post_type('subscriber', [
		'labels' => [
			'name' => 'Подписки на журнал',
			'singular_name' => 'Подписчик',
		],
		'public' => false,
		'show_ui' => true,
		'menu_icon' => 'dashicons-email-alt',
		'supports' => ['title'],
	]);
});

// Колонка "Имя" в списке подписчиков
add_filter('manage_edit-subscriber_columns', function ($columns) {
	$columns['subscriber_name'] = 'Имя';
	return $columns;
});
add_action('manage_subscriber_posts_custom_column', function ($column, $post_id) {
	if ($column === 'subscriber_name') {
		echo esc_html(get_post_meta($post_id, 'subscriber_name', true));
	}
}, 10, 2);

// Метабокс "Имя" при редактировании подписчика
add_action('add_meta_boxes', function () {
	add_meta_box('subscriber_name_box', 'Имя подписчика', function ($post) {
		$value = get_post_meta($post->ID, 'subscriber_name', true);
		echo '<input type="text" name="subscriber_name" value="' . esc_attr($value) . '" class="widefat">';
	}, 'subscriber', 'normal', 'default');
});
add_action('save_post_subscriber', function ($post_id) {
	if (array_key_exists('subscriber_name', $_POST)) {
		update_post_meta($post_id, 'subscriber_name', sanitize_text_field($_POST['subscriber_name']));
	}
});

// Ajax обработчик подписки
add_action('wp_ajax_nopriv_subscribe_user', 'subscribe_user');
add_action('wp_ajax_subscribe_user', 'subscribe_user');

function khag_get_subscribe_request_ip()
{
	$keys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'REMOTE_ADDR'];
	foreach ($keys as $key) {
		if (isset($_SERVER[$key])) {
			$ipList = explode(',', $_SERVER[$key]);
			foreach ($ipList as $ip) {
				$ip = trim($ip);
				if (filter_var($ip, FILTER_VALIDATE_IP)) {
					return $ip;
				}
			}
		}
	}

	return 'unknown';
}

function khag_track_subscribe_failure($ip)
{
	$limit = 5;
	$window = 15 * MINUTE_IN_SECONDS;
	$blockDuration = 30 * MINUTE_IN_SECONDS;

	$key = 'khag_subscribe_fail_' . md5($ip);
	$data = get_transient($key);

	if (!is_array($data) || (time() - $data['window_start']) > $window) {
		$data = [
			'count' => 0,
			'window_start' => time(),
		];
	}

	$data['count']++;

	set_transient($key, $data, $window);

	if ($data['count'] >= $limit) {
		set_transient('khag_subscribe_block_' . md5($ip), time(), $blockDuration);
	}

	error_log(sprintf('[khag_subscribe] Failed attempt from %s (count %d in %d seconds)', $ip, $data['count'], $window));
}

function khag_clear_subscribe_failures($ip)
{
	delete_transient('khag_subscribe_fail_' . md5($ip));
	delete_transient('khag_subscribe_block_' . md5($ip));
}

function khag_is_subscribe_ip_blocked($ip)
{
	return (bool) get_transient('khag_subscribe_block_' . md5($ip));
}

function khag_set_subscribe_cooldown()
{
	$path = defined('COOKIEPATH') ? COOKIEPATH : '/';
	$domain = defined('COOKIE_DOMAIN') ? COOKIE_DOMAIN : '';
	$secure = function_exists('is_ssl') ? is_ssl() : false;

	setcookie(
		'khag_subscribe_cd',
		(string) time(),
		time() + 60,
		$path,
		$domain,
		$secure,
		true
	);
}

function khag_subscribe_error($ip, $message)
{
	khag_track_subscribe_failure($ip);
	khag_set_subscribe_cooldown();
	wp_send_json_error($message);
}

function subscribe_user()
{
	check_ajax_referer('khag_subscribe_nonce', 'nonce');
	$ip = khag_get_subscribe_request_ip();

	if (khag_is_subscribe_ip_blocked($ip)) {
		khag_set_subscribe_cooldown();
		wp_send_json_error('Слишком много попыток. Попробуйте позже.');
	}

	if (isset($_COOKIE['khag_subscribe_cd'])) {
		$lastAttempt = (int) $_COOKIE['khag_subscribe_cd'];
		if ($lastAttempt && (time() - $lastAttempt) < 60) {
			khag_set_subscribe_cooldown();
			wp_send_json_error('Слишком частые отправки. Попробуйте позже.');
		}
	}

	$email = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
	$name = isset($_POST['name']) ? sanitize_text_field(wp_unslash($_POST['name'])) : '';
	$honeypot = isset($_POST['subscribe_hp']) ? trim(wp_unslash($_POST['subscribe_hp'])) : '';

	if (!empty($honeypot)) {
		khag_subscribe_error($ip, 'Не удалось сохранить подписку. Попробуйте ещё раз позже.');
	}

	if (!is_email($email)) {
		khag_subscribe_error($ip, 'Некорректный email');
	}
	if (empty($name)) {
		khag_subscribe_error($ip, 'Пожалуйста, укажите имя');
	}

	// Проверяем, нет ли уже такого подписчика
	$exists = get_posts([
		'post_type' => 'subscriber',
		'title' => $email,
		'posts_per_page' => 1,
		'fields' => 'ids'
	]);

	if ($exists) {
		khag_subscribe_error($ip, 'Вы уже подписаны!');
	}

	// Создаём нового подписчика и сохраняем имя в метаполе
	$post_id = wp_insert_post([
		'post_type' => 'subscriber',
		'post_title' => $email,
		'post_status' => 'publish'
	]);
	if ($post_id && !is_wp_error($post_id)) {
		update_post_meta($post_id, 'subscriber_name', $name);
		khag_clear_subscribe_failures($ip);
		khag_set_subscribe_cooldown();
		wp_send_json_success('Спасибо за подписку!');
	}

	khag_subscribe_error($ip, 'Не удалось сохранить подписку. Попробуйте ещё раз позже.');
}

// Добавляем страницу "Экспорт в CSV" для подписчиков
add_action('admin_menu', function () {
	add_submenu_page(
		'edit.php?post_type=subscriber',
		'Экспорт подписчиков',
		'Экспорт',
		'manage_options',
		'export-subscribers',
		'export_subscribers_page'
	);
});

// Страница экспорта
function export_subscribers_page()
{
?>
	<div class="wrap">
		<h1>Экспорт подписчиков</h1>
		<p>Cкачать CSV-файл</p>
		<form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
			<input type="hidden" name="action" value="export_subscribers_csv">
			<?php wp_nonce_field('export_subscribers_csv_nonce', 'export_subscribers_csv_nonce_field'); ?>
			<input type="submit" class="button button-primary" value="Скачать CSV">
		</form>
		<hr />
		<p>Cкачать Excel-файл</p>
		<form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
			<input type="hidden" name="action" value="export_subscribers_xls">
			<?php wp_nonce_field('export_subscribers_xls_nonce', 'export_subscribers_xls_nonce_field'); ?>
			<input type="submit" class="button" value="Скачать Excel (.xls)">
		</form>
	</div>
<?php
}

// Обработка экспорта CSV
add_action('admin_post_export_subscribers_csv', 'export_subscribers_to_csv');

function export_subscribers_to_csv()
{
	// Проверка безопасности
	if (
		empty($_POST['export_subscribers_csv_nonce_field']) ||
		!wp_verify_nonce($_POST['export_subscribers_csv_nonce_field'], 'export_subscribers_csv_nonce')
	) {
		wp_die('Ошибка безопасности');
	}

	// Получаем всех подписчиков
	$subscribers = get_posts([
		'post_type'      => 'subscriber',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'orderby'        => 'date',
		'order'          => 'ASC',
	]);

	if (empty($subscribers)) {
		wp_die('Нет подписчиков для экспорта.');
	}

	// Заголовки
	header('Content-Type: text/csv; charset=UTF-8');
	header('Content-Disposition: attachment; filename="subscribers-' . date('Y-m-d') . '.csv"');
	header('Pragma: no-cache');
	header('Expires: 0');

	$output = fopen('php://output', 'w');

	// Добавляем BOM для Excel
	fwrite($output, "\xEF\xBB\xBF");

	// Заголовки колонок
	fputcsv($output, ['Email', 'Имя', 'Дата подписки']);

	// Данные
	foreach ($subscribers as $s) {
		$name = get_post_meta($s->ID, 'subscriber_name', true);
		fputcsv($output, [$s->post_title, $name, $s->post_date]);
	}

	fclose($output);
	exit;
}

// Обработка экспорта в Excel (.xls с фиксированной шириной колонок)
add_action('admin_post_export_subscribers_xls', 'export_subscribers_to_xls');

function export_subscribers_to_xls()
{
	if (
		empty($_POST['export_subscribers_xls_nonce_field']) ||
		!wp_verify_nonce($_POST['export_subscribers_xls_nonce_field'], 'export_subscribers_xls_nonce')
	) {
		wp_die('Ошибка безопасности');
	}

	$subscribers = get_posts([
		'post_type'      => 'subscriber',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'orderby'        => 'date',
		'order'          => 'ASC',
	]);

	// Очищаем буферы перед заголовками
	while (ob_get_level()) {
		ob_end_clean();
	}

	nocache_headers();
	header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
	header('Content-Disposition: attachment; filename="subscribers-' . date('Y-m-d') . '.xls"');

	// BOM для корректного UTF-8 в Excel
	echo "\xEF\xBB\xBF";

	// HTML-таблица, которую Excel корректно откроет как лист с заданной шириной колонок
	echo '<html><head><meta charset="UTF-8"></head><body>';
	echo '<table border="1" cellspacing="0" cellpadding="4">';
	echo '<colgroup>';
	// Ширина колонок в пикселях (пример: Email шире, дата уже)
	echo '<col width="280" />';
	echo '<col width="220" />';
	echo '<col width="180" />';
	echo '</colgroup>';
	echo '<thead><tr><th>Email</th><th>Имя</th><th>Дата подписки</th></tr></thead>';
	echo '<tbody>';
	foreach ($subscribers as $s) {
		$email = esc_html($s->post_title);
		$name  = esc_html(get_post_meta($s->ID, 'subscriber_name', true));
		$date  = esc_html(mysql2date('Y-m-d H:i:s', $s->post_date));
		echo '<tr>';
		echo '<td>' . $email . '</td>';
		echo '<td>' . $name . '</td>';
		echo '<td>' . $date . '</td>';
		echo '</tr>';
	}
	echo '</tbody></table></body></html>';
	exit;
}

add_action('init', function () {
	$block_dir = get_template_directory() . '/gutenberg/info-section';
	if (file_exists($block_dir . '/block.json')) {
		register_block_type($block_dir);
	}
});

add_action('init', function () {
	$block_dir = get_template_directory() . '/gutenberg/materials-chart';
	if (file_exists($block_dir . '/block.json')) {
		register_block_type($block_dir);
	}
});

add_action('init', function () {
	$block_dir = get_template_directory() . '/gutenberg/expert-opinion';
	if (file_exists($block_dir . '/block.json')) {
		register_block_type($block_dir);
	}
});

// Преобразование названия месяца в родительный падеж
function khag_month_genitive($date_string)
{
	$months = array(
		// Русские названия (именительный падеж -> родительный)
		'Январь'    => 'Января',
		'Февраль'   => 'Февраля',
		'Март'      => 'Марта',
		'Апрель'    => 'Апреля',
		'Май'       => 'Мая',
		'Июнь'      => 'Июня',
		'Июль'      => 'Июля',
		'Август'    => 'Августа',
		'Сентябрь'  => 'Сентября',
		'Октябрь'   => 'Октября',
		'Ноябрь'    => 'Ноября',
		'Декабрь'   => 'Декабря',
		// Русские названия в нижнем регистре
		'январь'    => 'Января',
		'февраль'   => 'Февраля',
		'март'      => 'Марта',
		'апрель'    => 'Апреля',
		'май'       => 'Мая',
		'июнь'      => 'Июня',
		'июль'      => 'Июля',
		'август'    => 'Августа',
		'сентябрь'  => 'Сентября',
		'октябрь'   => 'Октября',
		'ноябрь'    => 'Ноября',
		'декабрь'   => 'Декабря'
	);

	return str_replace(array_keys($months), array_values($months), $date_string);
}
