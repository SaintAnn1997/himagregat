<!-- Архив журнала отключен -->

<?php
get_header();
?>

<main>

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

    <section class="journal-section">
        <div class="container">

            <div class="journal-section__items">

                <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

                        <div class="journal-section__item">
                            <div class="journal-section__item-title">
                                <?php the_title(); ?>
                                <span>- <?php echo get_the_date('F Y'); ?></span>
                            </div>

                            <a href="<?php the_permalink(); ?>" target="_blank">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php
                                    $thumbnail_id = get_post_thumbnail_id();
                                    $alt_text = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
                                    $alt_text = $alt_text ? $alt_text : get_the_title();
                                    ?>
                                    <img class="journal-section__item-img"
                                        src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'large'); ?>"
                                        alt="<?php echo esc_attr($alt_text); ?>">
                                <?php else : ?>
                                    <img class="journal-section__item-img"
                                        src="<?php echo get_template_directory_uri(); ?>/assets/imgs/journal-img-1.png"
                                        alt="<?php echo esc_attr(get_the_title()); ?>">
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

                <?php endwhile;
                endif; ?>

            </div>

        </div>
    </section>
</main>

<?php
get_footer();
?>