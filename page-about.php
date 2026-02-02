<?php
/*
Template Name: Журнал
*/
?>

<?php
get_header();
?>

<main class="main">

    <section class="page-header">
        <div class="container">

            <h1 class="page-header__title"><?php the_title(); ?></h1>

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

    <section class="archive-tabs archive-tabs--journal">
        <div class="container">
            <div class="archive-tabs__wrapper">
                <button class="archive-tabs__item archive-tabs__item--active">О журнале</button>
                <button class="archive-tabs__item">Архив</button>
                <button class="archive-tabs__item">Реклама</button>
                <button class="archive-tabs__item">Подписка</button>
            </div>
        </div>
    </section>
    <hr class="archive-tabs__line">

    <section class="info-section js-tabs-content">
        <div class="container">

            <?php khag_journal_notice('journal-notice--sidebar journal-notice--top'); ?>

            <div class="info-section__wrapper">

                <div class="info-section__content">
                    <?php
                    $about_content = get_field('about_journal');
                    if ($about_content) {
                        echo wp_kses_post($about_content);
                    }
                    ?>
                </div>

                <div class="info-section__sidebar">
                    <?php dynamic_sidebar('sidebar-journal'); ?>
                </div>

            </div>

        </div>
    </section>

    <section class="journal-section journal-section--archive js-tabs-content">

            <div class="container">

                <?php khag_journal_notice(); ?>

                <div class="journal-section__items">

                    <?php
                    // Получаем ID последнего журнала чтобы исключить его из списка
                    $latest = khag_get_latest_magazine();
                    $exclude_id = $latest ? array($latest['id']) : array();

                    $args = [
                        'post_type'      => 'magazine',
                        'posts_per_page' => -1,
                        'order'          => 'DESC',
                        'post__not_in'   => $exclude_id, // Исключаем последний журнал
                    ];

                    $query = new WP_Query($args);

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
                    } else {
                        echo '<p>Журналы не найдены.</p>';
                    }

                    wp_reset_postdata();
                    ?>

                    <div class="journal-section__item">
                        <?php dynamic_sidebar('sidebar-archive-journal'); ?>
                    </div>

                </div>

            </div>
    </section>

    <section class="info-section info-section--ads js-tabs-content">
        <div class="container">

            <?php khag_journal_notice('journal-notice--sidebar journal-notice--top'); ?>

            <div class="info-section__wrapper">

                <div class="info-section__content">
                    <?php
                    $journal_ads = get_field('journal-ads');
                    if ($journal_ads) {
                        echo wp_kses_post($journal_ads);
                    }
                    ?>
                </div>

                <div class="info-section__sidebar">
                    <?php dynamic_sidebar('sidebar-journal'); ?>
                </div>

            </div>

        </div>
    </section>

    <section class="info-section info-section--subscribe js-tabs-content">
        <div class="container">

            <?php khag_journal_notice('journal-notice--sidebar journal-notice--top'); ?>

            <div class="info-section__wrapper">

                <div class="info-section__content">

                    <?php
                    $journal_subscribe = get_field('journal-subscribe');
                    if ($journal_subscribe) {
                        echo wp_kses_post($journal_subscribe);
                    }
                    ?>

                    <!-- <div class="subscription-form">
                        <h3 class="subscription-form__title">Заполните форму</h3>

                        <form class="subscription-form__form">
                            <div class="subscription-form__row">
                                <label class="subscription-form__label">
                                    <span class="subscription-form__label-text">Ваше имя <span class="required">*</span></span>
                                    <input type="text" name="name" class="subscription-form__input" required>
                                </label>
                            </div>

                            <div class="subscription-form__row">
                                <label class="subscription-form__label">
                                    <span class="subscription-form__label-text">Телефон <span class="required">*</span></span>
                                    <input type="tel" name="phone" class="subscription-form__input" required>
                                </label>
                            </div>

                            <div class="subscription-form__row">
                                <label class="subscription-form__label">
                                    <span class="subscription-form__label-text">Предприятие</span>
                                    <input type="text" name="company" class="subscription-form__input">
                                </label>
                            </div>

                            <div class="subscription-form__footer">
                                <span class="subscription-form__note"><span class="required">*</span> - обязательны к заполнению</span>
                                <button type="submit" class="subscription-form__submit btn">Отправить</button>
                            </div>
                        </form>
                    </div> -->

                </div>

                <div class="info-section__sidebar">
                    <?php dynamic_sidebar('sidebar-journal'); ?>
                </div>

            </div>

        </div>
    </section>

    <?php get_template_part('template-parts/subscribe-block'); ?>

</main>

<?php
get_footer();
?>