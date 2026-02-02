<?php
get_header();
?>

<main class="main">

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

    <section class="archive-tabs">
        <div class="container">
            <div class="archive-tabs__wrapper">
                <button class="archive-tabs__item archive-tabs__item--active" data-category="all">Все</button>

                <?php
                $terms = get_terms([
                    'taxonomy' => 'news_category',
                    'hide_empty' => true,
                    'order' => 'DESC',
                ]);

                if (!empty($terms) && !is_wp_error($terms)) {
                    $other_term = null;
                    $regular_terms = [];
                    
                    foreach ($terms as $term) {
                        if ($term->name === 'Другое') {
                            $other_term = $term;
                        } else {
                            $regular_terms[] = $term;
                        }
                    }
                    
                    foreach ($regular_terms as $term) {
                        echo '<button class="archive-tabs__item" data-category="' . esc_attr($term->term_id) . '">' . esc_html($term->name) . '</button>';
                    }
                    
                    if ($other_term) {
                        echo '<button class="archive-tabs__item" data-category="' . esc_attr($other_term->term_id) . '">' . esc_html($other_term->name) . '</button>';
                    }
                }
                ?>
            </div>
        </div>
    </section>

    <section class="archive-news">
        <div class="container">

            <div class="archive-news__items" id="news-container">
                <?php get_template_part('template-parts/archive-news', null, ['category_id' => 'all', 'paged' => 1, 'posts_per_page' => 10]); ?>
            </div>

            <div class="archive-news__loader" id="news-loader" style="display:none;">
                <svg fill="#009652FF" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12,1A11,11,0,1,0,23,12,11,11,0,0,0,12,1Zm0,19a8,8,0,1,1,8-8A8,8,0,0,1,12,20Z" opacity=".25" />
                    <path d="M12,4a8,8,0,0,1,7.89,6.7A1.53,1.53,0,0,0,21.38,12h0a1.5,1.5,0,0,0,1.48-1.75,11,11,0,0,0-21.72,0A1.5,1.5,0,0,0,2.62,12h0a1.53,1.53,0,0,0,1.49-1.3A8,8,0,0,1,12,4Z">
                        <animateTransform attributeName="transform" type="rotate" dur="0.75s" values="0 12 12;360 12 12" repeatCount="indefinite" />
                    </path>
                </svg>
            </div>


        </div>

    </section>

    <?php get_template_part('template-parts/subscribe-block'); ?>

</main>

<?php
get_footer();
?>