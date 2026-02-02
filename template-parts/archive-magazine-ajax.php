<?php
$paged = $args['paged'] ?? 1;
$posts_per_page = $args['posts_per_page'] ?? 11;
$latest = khag_get_latest_magazine();
$exclude_id = $latest ? array($latest['id']) : array();

$query_args = [
    'post_type'      => 'magazine',
    'posts_per_page' => $posts_per_page,
    'paged'          => $paged,
    'order'          => 'DESC',
    'post__not_in'   => $exclude_id,
];

$query = new WP_Query($query_args);

if ($query->have_posts()) {
    while ($query->have_posts()) {
        $query->the_post(); ?>
        
        <div class="journal-section__item">
            <div class="journal-section__item-title"><?php the_title(); ?> <span>- <?php echo get_the_date('F Y'); ?></span></div>
            <a href="<?php the_permalink(); ?>" target="_blank">
                <?php
                $thumbnail_id = get_post_thumbnail_id();
                $alt_text = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
                $alt_text = $alt_text ? $alt_text : get_the_title();
                ?>
                <img class="journal-section__item-img"
                    src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'large'); ?>"
                    alt="<?php echo esc_attr($alt_text); ?>">
            </a>
            <?php
            $pdf_link = get_field('journal_pdf_file');
            if ($pdf_link) :
            ?>
                <a href="<?php echo esc_url($pdf_link); ?>"
                    class="journal-section__item-pdf"
                    target="_blank">Версия PDF</a>
            <?php endif; ?>
        </div>
        
    <?php }
    
    $total_pages = $query->max_num_pages;
    if ($total_pages > 1) : ?>
        <nav class="pagination" aria-label="Постраничная навигация по архиву">
            <ul class="pagination__items">
                <?php if ($paged > 1) : ?>
                    <li class="pagination__item pagination__prev">
                        <a href="#" data-page="<?php echo ($paged - 1); ?>">
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
                    echo '<li class="pagination__item' . $active_class . '"><a href="#" data-page="' . $i . '">' . $i . '</a></li>';
                }
                ?>                <?php if ($paged < $total_pages) : ?>
                    <li class="pagination__item pagination__next">
                        <a href="#" data-page="<?php echo ($paged + 1); ?>">
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
    <?php endif;
    
} else {
    echo '<p>Журналы не найдены.</p>';
}

wp_reset_postdata();
