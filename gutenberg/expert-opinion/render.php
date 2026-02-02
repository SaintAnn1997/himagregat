<?php
$title = get_field('expert-title');
$quote = get_field('expert-descr');
$name = get_field('expert-name');
$position = get_field('expert-post');
$img = get_field('expert-img');
$imgUrl = is_array($img) && isset($img['url']) ? $img['url'] : '';
?>

<div class="expert-opinion">
    <div class="expert-opinion__title"><?= esc_html($title); ?></div>
    <div class="expert-opinion__descr"><?= esc_html($quote); ?></div>
    <div class="expert-opinion__bottom">
        <?php if ($imgUrl): ?>
            <img class="expert-opinion__bottom-img" src="<?= esc_url($imgUrl); ?>" alt="<?= esc_attr($name); ?>">
        <?php endif; ?>
        <div class="expert-opinion__bottom-info">
            <div class="expert-opinion__bottom-name"><?= esc_html($name); ?></div>
            <div class="expert-opinion__bottom-position"><?= esc_html($position); ?></div>
        </div>
    </div>
</div>