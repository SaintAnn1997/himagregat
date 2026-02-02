<?php
/*
Template Name: Выставки
*/

get_header();

if (is_singular('tribe_events')) {
    tribe_get_template_part('single-event');
    get_footer();
    return;
}
?>

<main>

    <section class="page-header">
        <div class="container">

            <h1 class="page-header__title"><?php echo apply_filters('tribe_events_title', 'Календарь событий', true); ?></h1>

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

    <section class="archive-section section-margins">
        <div class="container">

            <div class="archive-section__cards">

                <?php
                $paged = (get_query_var('paged')) ? get_query_var('paged') : ((get_query_var('page')) ? get_query_var('page') : 1);

                $events = tribe_get_events([
                    'posts_per_page' => 8,
                    'paged'          => $paged,
                    'post_status'    => ['publish', 'future'],
                    'ends_after'     => current_time('Y-m-d H:i:s'),
                    'orderby'        => 'start_date',
                    'order'          => 'ASC',
                ]);
                if ($events) :
                    foreach ($events as $event) :
                        $event_id = $event->ID;
                ?>
                        <div class="archive-section__card">
                            <?php if (has_post_thumbnail($event_id)) : ?>
                                <img class="archive-section__card-img" src="<?php echo get_the_post_thumbnail_url($event_id); ?>" alt="<?php echo esc_attr(get_the_title($event_id)); ?>">
                            <?php endif; ?>
                            <div class="archive-section__card-bottom">
                                <?php
                                $event_title = get_the_title($event_id);
                                if ($event_title) :
                                ?>
                                    <div class="archive-section__card-title"><?php echo esc_html($event_title); ?></div>
                                <?php endif; ?>

                                <div class="archive-section__card-descr"><?php echo esc_html(khag_get_trimmed_excerpt(220, $event_id)); ?></div>

                                <?php
                                $venue_name = tribe_get_venue($event_id);
                                if ($venue_name) :
                                ?>
                                    <div class="archive-section__card-geo">
                                        <?php echo esc_html($venue_name); ?>
                                    </div>
                                <?php endif; ?>

                                <a href="<?php echo esc_url(tribe_get_event_link($event_id)); ?>" class="archive-section__card-more">Подробнее</a>
                            </div>
                        </div>
                <?php
                    endforeach;
                else :
                    echo '<p>Нет подходящих выставок.</p>';
                endif;
                ?>

            </div>

            <?php
            $total_events = tribe_get_events([
                'post_status'    => ['publish', 'future'],
                'ends_after'     => current_time('Y-m-d H:i:s'),
                'posts_per_page' => -1,
            ]);
            $total_pages = ceil(count($total_events) / 8);

            if ($total_pages > 1) :
            ?>
                <nav class="pagination" aria-label="Постраничная навигация по выставкам">
                    <ul class="pagination__items">
                        <?php if ($paged > 1) : ?>
                            <li class="pagination__item pagination__prev">
                                <a href="<?php echo esc_url(add_query_arg('page', $paged - 1)); ?>">
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
                        echo '<li class="pagination__item' . $active_class . '"><a href="' . esc_url(add_query_arg('page', $i)) . '">' . $i . '</a></li>';
                    }
                    ?>                        <?php if ($paged < $total_pages) : ?>
                            <li class="pagination__item pagination__next">
                                <a href="<?php echo esc_url(add_query_arg('page', $paged + 1)); ?>">
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

</main>

<?php
get_footer();
