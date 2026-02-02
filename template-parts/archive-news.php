<?php

$category_id = $args['category_id'] ?? 'all';
$paged = $args['paged'] ?? 1;
$posts_per_page = $args['posts_per_page'] ?? 10;

$offset = 0;
if ($paged > 1) {
    $offset = 10 + (($paged - 2) * 5);
}

$query_args = [
    'post_type'      => 'news',
    'posts_per_page' => $posts_per_page,
    'offset'         => $offset,
    'order'          => 'DESC',
    'orderby'        => 'date',
    'ignore_sticky_posts' => true, 
];

if ($category_id !== 'all') {
    $query_args['tax_query'] = [
        [
            'taxonomy' => 'news_category',
            'field'    => 'term_id',
            'terms'    => intval($category_id),
        ]
    ];
}

$query = new WP_Query($query_args);

if ($query->have_posts()) {
    $post_index = 0;
    $global_index = $offset;
    $post_titles = [];
    
    while ($query->have_posts()) {
        $query->the_post();
        $post_titles[] = get_the_ID() . ':' . get_the_title();
?>
        <div class="archive-news__item<?php echo ($global_index === 0) ? ' archive-news__item--first' : ''; ?>">
            <?php if (has_post_thumbnail()) : ?>
                <img class="archive-news__item-img" src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'large'); ?>"
                    alt="<?php echo esc_attr(get_the_title()); ?>">
            <?php endif; ?>

            <div class="archive-news__item-info">
                <div class="archive-news__item-header">
                    <div class="archive-news__item-data"><?php echo get_the_date('j F Y'); ?></div>
                    <?php
                    $post_terms = get_the_terms(get_the_ID(), 'news_category');
                    if ($post_terms && !is_wp_error($post_terms)) {
                        $term = array_shift($post_terms);
                        echo '<div class="archive-news__item-tag tag">' . esc_html($term->name) . '</div>';
                    }
                    ?>
                </div>

                <div class="archive-news__item-title"><?php the_title(); ?></div>

                <div class="archive-news__item-descr">
                    <?php echo esc_html(khag_get_trimmed_excerpt(220)); ?>
                </div>

                <a href="<?php echo get_permalink(); ?>" target="_blank" class="archive-news__item-link">Подробнее</a>

            </div>
        </div>
<?php
        $post_index++;
        $global_index++;
    }
} else {
    echo '<p>Новости не найдены.</p>';
}

wp_reset_postdata();
