<?php
/*
Template Name: Главная
*/
?>

<?php
get_header();
?>

<main class="main">

    <?php
    $bg_img_desktop = get_field('hero_background_img');
    $bg_img_mobile = get_field('hero_background_image_mobile');
    ?>

    <section class="archive-section archive-section--news section-margins">
        <div class="container">

            <div class="section-header section-header--home">
                <h2 class="section-header__title">
                    <span class="section-header__icon">
                        <svg width="13" height="18" viewBox="0 0 13 18" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M5.61078 0.000113673H8.39179L13 9.00006L8.39179 18H5.61078L10.219 9.00006L5.61078 0.000113673ZM0 17.9999H4.60541L9.21361 8.99994L4.60541 0H0L4.60821 8.99994L0 17.9999Z"
                                fill="#373435" />
                        </svg>
                    </span>
                    <?php
                    $news_post_type = get_post_type_object('news');
                    echo $news_post_type ? esc_html($news_post_type->labels->name) : 'НОВОСТИ';
                    ?>
                </h2>
                <a href="<?php echo get_post_type_archive_link('news'); ?>" class="section-header__link section-header__link-top">Все новости</a>
            </div>

            <div class="archive-section__cards">

                <?php
                $news_args = array(
                    'post_type' => 'news',
                    'posts_per_page' => 4,
                    'orderby' => 'date',
                    'order' => 'DESC'
                );
                $news_query = new WP_Query($news_args);

                if ($news_query->have_posts()) :
                    while ($news_query->have_posts()) : $news_query->the_post();
                        $categories = get_the_terms(get_the_ID(), 'news_category');
                        $category_name = ($categories && !is_wp_error($categories)) ? $categories[0]->name : '';
                ?>

                        <div class="archive-section__card">
                            <!-- <div class="archive-section__card-header">
                                <span class="archive-section__card-tag tag"><?php echo esc_html($category_name); ?></span>
                            </div> -->

                            <div class="archive-section__card-bottom">
                                <div class="archive-section__card-date"><?php echo get_the_date('d.m.Y'); ?></div>

                                <div class="archive-section__card-title">
                                    <?php echo esc_html(khag_get_trimmed_title(160)); ?>
                                </div>

                                <div class="archive-section__card-descr">
                                    <?php echo esc_html(khag_get_trimmed_excerpt(234)); ?>
                                </div>

                                <a href="<?php the_permalink(); ?>" target="_blank" class="archive-section__card-more">Подробнее</a>
                            </div>
                        </div>

                <?php
                    endwhile;
                    wp_reset_postdata();
                endif;
                ?>

            </div>

            <a href="<?php echo get_post_type_archive_link('news'); ?>" class="section-header__link section-header__link-bottom">Все новости</a>

        </div>
    </section>

    <?php
    $hero_post = get_post(1898);
    if ($hero_post) :
        $hero_title = get_the_title(1898);
        $hero_excerpt = get_the_excerpt(1898);
        $hero_link = get_permalink(1898);
    ?>
        <section class="hero section-margins">
            <div class="container">
                <div class="hero__inner">
                    <picture class="hero__bg">
                        <source srcset="<?php echo esc_url($bg_img_mobile); ?>" media="(max-width: 430px)">
                        <img src="https://himagregat-info.ru/wp-content/uploads/2026/01/foto-5-1-scaled.jpg" />
                    </picture>

                    <h1 class="hero__title"><?php echo esc_html($hero_title); ?></h1>
                    <div class="hero__descr" style="max-width:90%;"><?php echo esc_html($hero_excerpt); ?></div>

                    <a href="<?php echo esc_url($hero_link); ?>" class="hero__btn btn" target="_blank"
                        aria-label="Подробнее">
                        <span>Читать статью</span>
                        <svg width="23" height="23" viewBox="0 0 23 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M19.5901 11.5764L2.78121 11.5764" stroke="white" stroke-width="1.5"
                                stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M13.1252 18.0414L19.5901 11.5764L13.4484 5.43468" stroke="white" stroke-width="1.5"
                                stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </a>

                </div>
            </div>
        </section>
    <?php endif; ?>

    <section class="archive-section archive-section--alt-tags section-margins">
        <div class="container">

            <div class="section-header section-header--home">
                <h2 class="section-header__title">
                    <span class="section-header__icon">
                        <svg width="13" height="18" viewBox="0 0 13 18" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M5.61078 0.000113673H8.39179L13 9.00006L8.39179 18H5.61078L10.219 9.00006L5.61078 0.000113673ZM0 17.9999H4.60541L9.21361 8.99994L4.60541 0H0L4.60821 8.99994L0 17.9999Z"
                                fill="#373435" />
                        </svg>
                    </span>
                    <?php
                    $technic_post_type = get_post_type_object('technic');
                    echo $technic_post_type ? esc_html($technic_post_type->labels->name) : 'техника и технологии';
                    ?>
                </h2>
                <a href="<?php echo get_post_type_archive_link('technic'); ?>" class="section-header__link section-header__link-top">Все статьи</a>
            </div>

            <div class="archive-section__cards">

                <?php
                $technic_args = array(
                    'post_type' => 'technic',
                    'posts_per_page' => 3,
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'post__not_in' => array(1898),
                );
                $technic_query = new WP_Query($technic_args);

                if ($technic_query->have_posts()) :
                    while ($technic_query->have_posts()) : $technic_query->the_post();
                        $categories = get_the_terms(get_the_ID(), 'technic_category');
                        $category_name = ($categories && !is_wp_error($categories)) ? $categories[0]->name : '';
                        $thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'large');
                ?>

                        <div class="archive-section__card">
                            <div class="archive-section__card-header">
                                <?php if ($thumbnail) : ?>
                                    <img class="archive-section__card-img" src="<?php echo esc_url($thumbnail); ?>" alt="<?php the_title_attribute(); ?>">
                                <?php endif; ?>
                                <!-- <span class="archive-section__card-tag tag"><?php echo esc_html($category_name); ?></span> -->
                            </div>

                            <div class="archive-section__card-bottom">
                                <div class="archive-section__card-date"><?php echo get_the_date('d.m.Y'); ?></div>

                                <div class="archive-section__card-title">
                                    <?php echo esc_html(khag_get_trimmed_title(160)); ?>
                                </div>

                                <div class="archive-section__card-descr">
                                    <?php echo esc_html(khag_get_trimmed_excerpt(234)); ?>
                                </div>

                                <a href="<?php the_permalink(); ?>" target="_blank" class="archive-section__card-more">Подробнее</a>
                            </div>
                        </div>

                <?php
                    endwhile;
                    wp_reset_postdata();
                endif;
                ?>

                <div class="archive-section__card">
                    <!-- Виджет WP Календарь событий -->
                    <?php dynamic_sidebar('sidebar-archive-technic'); ?>
                    <!-- Виджет WP Календарь событий -->
                </div>

                <a href="<?php echo get_post_type_archive_link('technic'); ?>" class="section-header__link section-header__link-bottom">Все статьи</a>

            </div>
    </section>

    <section class="analytics-section section-margins">
        <div class="container">

            <div class="section-header section-header--home">
                <h2 class="section-header__title">
                    <span class="section-header__icon">
                        <svg width="13" height="18" viewBox="0 0 13 18" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M5.61078 0.000113673H8.39179L13 9.00006L8.39179 18H5.61078L10.219 9.00006L5.61078 0.000113673ZM0 17.9999H4.60541L9.21361 8.99994L4.60541 0H0L4.60821 8.99994L0 17.9999Z"
                                fill="#373435" />
                        </svg>
                    </span>
                    <?php
                    $analytics_post_type = get_post_type_object('analytics');
                    echo $analytics_post_type ? esc_html($analytics_post_type->labels->name) : 'аналитика и мнение';
                    ?>
                </h2>
                <a href="<?php echo get_post_type_archive_link('analytics'); ?>" class="section-header__link section-header__link-top">Все статьи</a>
            </div>

            <div class="analytics-section__articles">

                <?php
                $analytics_args = array(
                    'post_type' => 'analytics',
                    'posts_per_page' => 5,
                    'orderby' => 'date',
                    'order' => 'DESC'
                );
                $analytics_query = new WP_Query($analytics_args);

                if ($analytics_query->have_posts()) :
                    $counter = 0;

                    // Большие статьи (первые 2)
                    echo '<div class="analytics-section__main">';
                    while ($analytics_query->have_posts() && $counter < 2) :
                        $analytics_query->the_post();
                        $counter++;
                        $categories = get_the_terms(get_the_ID(), 'analytics_category');
                        $category_name = ($categories && !is_wp_error($categories)) ? $categories[0]->name : '';
                        $thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'large');
                        $bg_style = $thumbnail ? 'style="background-image: url(' . esc_url($thumbnail) . ');"' : '';
                ?>
                        <a href="<?php the_permalink(); ?>" target="_blank" class="analytics-section__article analytics-section__article--big" <?php echo $bg_style; ?>>
                            <!-- <div class="analytics-section__article-tag tag"><?php echo esc_html($category_name); ?></div> -->
                            <div class="analytics-section__article-title">
                                <?php the_title(); ?>
                            </div>
                            <div class="analytics-section__article-date"><?php echo get_the_date('d.m.Y'); ?></div>
                        </a>
                    <?php
                    endwhile;
                    echo '</div>';

                    // Маленькие статьи (следующие 3)
                    echo '<div class="analytics-section__side">';
                    while ($analytics_query->have_posts() && $counter < 5) :
                        $analytics_query->the_post();
                        $counter++;
                        $categories = get_the_terms(get_the_ID(), 'analytics_category');
                        $category_name = ($categories && !is_wp_error($categories)) ? $categories[0]->name : '';
                        $thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                    ?>
                        <a href="<?php the_permalink(); ?>" target="_blank" class="analytics-section__article analytics-section__article--small">
                            <div class="analytics-section__article-info">
                                <!-- <div class="analytics-section__article-tag tag"><?php echo esc_html($category_name); ?></div> -->
                                <div class="analytics-section__article-title"><?php the_title(); ?></div>
                                <div class="analytics-section__article-descr">
                                    <?php echo esc_html(khag_get_trimmed_excerpt(30)); ?>
                                </div>
                                <div class="analytics-section__article-date"><?php echo get_the_date('d.m.Y'); ?></div>
                            </div>

                            <?php if ($thumbnail) : ?>
                                <img class="analytics-section__article-img" src="<?php echo esc_url($thumbnail); ?>" alt="<?php the_title_attribute(); ?>">
                            <?php endif; ?>
                        </a>
                <?php
                    endwhile;
                    echo '</div>';

                    wp_reset_postdata();
                endif;
                ?>

                <a href="<?php echo get_post_type_archive_link('analytics'); ?>" class="section-header__link section-header__link-bottom">Все статьи</a>

            </div>

    </section>

    <section class="journal-section section-margins">
        <div class="container">

            <div class="section-header section-header--home">
                <h2 class="section-header__title">
                    <span class="section-header__icon">
                        <svg width="13" height="18" viewBox="0 0 13 18" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M5.61078 0.000113673H8.39179L13 9.00006L8.39179 18H5.61078L10.219 9.00006L5.61078 0.000113673ZM0 17.9999H4.60541L9.21361 8.99994L4.60541 0H0L4.60821 8.99994L0 17.9999Z"
                                fill="#373435" />
                        </svg>
                    </span>
                    <?php
                    $magazine_post_type = get_post_type_object('magazine');
                    echo $magazine_post_type ? esc_html($magazine_post_type->labels->name) : 'ЖУРНАЛ';
                    ?>
                </h2>
                <a href="<?php echo bloginfo('template_url'); ?>/about" class="section-header__link section-header__link-top">Все выпуски</a>
            </div>

            <div class="journal-section__items">

                <?php
                $magazine_args = array(
                    'post_type' => 'magazine',
                    'posts_per_page' => 3,
                    'orderby' => 'date',
                    'order' => 'DESC'
                );
                $magazine_query = new WP_Query($magazine_args);

                if ($magazine_query->have_posts()) :
                    while ($magazine_query->have_posts()) : $magazine_query->the_post();
                        $thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'large');
                ?>

                        <div class="journal-section__item">
                            <div class="journal-section__item-title"><?php the_title(); ?><span> - <?php echo get_the_date('F Y'); ?></span></div>
                            <a href="<?php the_permalink(); ?>" target="_blank">
                                <?php if ($thumbnail) : ?>
                                    <img class="journal-section__item-img" src="<?php echo esc_url($thumbnail); ?>" alt="<?php the_title_attribute(); ?>">
                                <?php endif; ?>
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

                <?php
                    endwhile;
                    wp_reset_postdata();
                endif;
                ?>

            </div>

            <a href="<?php echo get_post_type_archive_link('magazine'); ?>" class="section-header__link section-header__link-bottom">Все выпуски</a>

        </div>
    </section>

</main>

<?php
get_footer();
?>