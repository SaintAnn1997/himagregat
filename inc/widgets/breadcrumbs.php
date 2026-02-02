<?php

// Хлебные крошки для KhimAgregati
function khag_get_breadcrumbs()
{
    $text['home']     = __('Главная', 'khag');
    $text['category'] = __('Архив', 'khag') . ' "%s"';
    $text['search']   = __('Результаты поиска', 'khag') . ' "%s"';
    $text['tag']      = __('Тег', 'khag') . ' "%s"';
    $text['author']   = __('Автор', 'khag') . ' %s';
    $text['404']      = __('Ошибка 404', 'khag');

    $show_current   = 1;
    $show_on_home   = 0;
    $show_home_link = 1;
    $show_title     = 1;
    $delimiter      = '<svg width="7" height="10" viewBox="0 0 7 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M3.02119 6.31516e-05H4.51866L7 5.00003L4.51866 10H3.02119L5.50253 5.00003L3.02119 6.31516e-05ZM0 9.99994H2.47983L4.96118 4.99997L2.47983 0H0L2.48134 4.99997L0 9.99994Z"
                            fill="#373435" fill-opacity="0.5" />
                    </svg>';
    $before         = '<span class="breadcrumbs__link">';
    $after          = '</span>';

    global $post;
    $home_link    = home_url('/');
    $link_before  = '';
    $link_after   = '';
    $link_attr    = ' class="breadcrumbs__link breadcrumbs__link--active"';
    $link         = $link_before . '<a' . $link_attr . ' href="%1$s">%2$s</a>' . $link_after;
    $parent_id    = isset($post) && is_object($post) && isset($post->post_parent) ? $post->post_parent : 0;
    $parent_id_2  = $parent_id;
    $frontpage_id = get_option('page_on_front');

    if (is_home() || is_front_page()) {
        if ($show_on_home == 1) {
            echo '<div class="breadcrumbs"><a href="' . $home_link . '" class="breadcrumbs__link breadcrumbs__link--active">' . $text['home'] . '</a></div>';
        }
    } else {
        echo '<div class="breadcrumbs">';
        if ($show_home_link == 1) {
            echo sprintf($link, $home_link, $text['home']);
            if ($frontpage_id == 0 || $parent_id != $frontpage_id) echo $delimiter;
        }

        // Кастомная таксономия (универсальная обработка)
        if (is_tax()) {
            $current_term = get_queried_object();
            $taxonomy = get_taxonomy($current_term->taxonomy);

            // Получаем связанный тип поста
            if (!empty($taxonomy->object_type)) {
                $post_type = $taxonomy->object_type[0];
                $post_type_obj = get_post_type_object($post_type);

                // Ссылка на архив типа поста
                printf($link, get_post_type_archive_link($post_type), $post_type_obj->labels->name);
                echo $delimiter;
            }

            // Текущая категория
            echo $before . $current_term->name . $after;
        }
        // Архив кастомного типа постов (универсальная обработка)
        elseif (is_post_type_archive()) {
            $post_type_obj = get_queried_object();
            echo $before . $post_type_obj->labels->name . $after;
        }
        // Отдельная запись кастомного типа поста (универсальная обработка)
        elseif (is_single() && get_post_type() != 'post') {
            $post_type = get_post_type();
            $post_type_obj = get_post_type_object($post_type);

            // Для CPT magazine ссылка на страницу /about/
            if ($post_type === 'magazine') {
                printf($link, home_url('/about/'), __('Журнал', 'khag'));
            } else {
                printf($link, get_post_type_archive_link($post_type), $post_type_obj->labels->name);
            }
            echo $delimiter;

            // Текущая запись
            if ($show_current == 1) echo $before . get_the_title() . $after;
        }
        // Стандартные категории
        elseif (is_category()) {
            $this_cat = get_category(get_query_var('cat'), false);
            if ($this_cat->parent != 0) {
                $cats = get_category_parents($this_cat->parent, true, $delimiter);
                if ($show_current == 0) $cats = preg_replace("#^(.+)" . preg_quote($delimiter, '#') . "$#", "$1", $cats);
                $cats = str_replace('<a', $link_before . '<a class="breadcrumbs__link breadcrumbs__link--active"', $cats);
                $cats = str_replace('</a>', '</a>' . $link_after, $cats);
                if ($show_title == 0) $cats = preg_replace('/ title="(.*?)"/', '', $cats);
                echo $cats;
            }
            if ($show_current == 1) echo $before . sprintf($text['category'], single_cat_title('', false)) . $after;
        } elseif (is_search()) {
            echo $before . sprintf($text['search'], get_search_query()) . $after;
        } elseif (is_day()) {
            echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
            echo sprintf($link, get_month_link(get_the_time('Y'), get_the_time('m')), get_the_time('F')) . $delimiter;
            echo $before . get_the_time('d') . $after;
        } elseif (is_month()) {
            echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
            echo $before . get_the_time('F') . $after;
        } elseif (is_year()) {
            echo $before . get_the_time('Y') . $after;
        } elseif (is_single() && !is_attachment()) {
            if (get_post_type() != 'post') {
                $post_type = get_post_type_object(get_post_type());
                $slug = $post_type->rewrite;
                printf($link, $home_link . $slug['slug'] . '/', $post_type->labels->singular_name);
                if ($show_current == 1) echo $delimiter . $before . get_the_title() . $after;
            } else {
                $cat = get_the_category();
                if ($cat) {
                    $cat = $cat[0];
                    $cats = get_category_parents($cat, true, $delimiter);
                    if ($show_current == 0) $cats = preg_replace("#^(.+)" . preg_quote($delimiter, '#') . "$#", "$1", $cats);
                    $cats = str_replace('<a', $link_before . '<a class="breadcrumbs__link breadcrumbs__link--active"', $cats);
                    $cats = str_replace('</a>', '</a>' . $link_after, $cats);
                    if ($show_title == 0) $cats = preg_replace('/ title="(.*?)"/', '', $cats);
                    echo $cats;
                }
                if ($show_current == 1) echo $before . get_the_title() . $after;
            }
        } elseif (!is_single() && !is_page() && get_post_type() != 'post' && !is_404()) {
            $post_type = get_post_type_object(get_post_type());
            echo $before . $post_type->labels->singular_name . $after;
        } elseif (is_page() && !$parent_id) {
            if ($show_current == 1) echo $before . get_the_title() . $after;
        } elseif (is_page() && $parent_id) {
            if ($parent_id != $frontpage_id) {
                $breadcrumbs = array();
                while ($parent_id) {
                    $page = get_page($parent_id);
                    if ($parent_id != $frontpage_id) {
                        $breadcrumbs[] = sprintf($link, get_permalink($page->ID), get_the_title($page->ID));
                    }
                    $parent_id = $page->post_parent;
                }
                $breadcrumbs = array_reverse($breadcrumbs);
                for ($i = 0; $i < count($breadcrumbs); $i++) {
                    echo $breadcrumbs[$i];
                    if ($i != count($breadcrumbs) - 1) echo $delimiter;
                }
            }
            if ($show_current == 1) {
                if ($show_home_link == 1 || ($parent_id_2 != 0 && $parent_id_2 != $frontpage_id)) echo $delimiter;
                echo $before . get_the_title() . $after;
            }
        } elseif (is_tag()) {
            echo $before . sprintf($text['tag'], single_tag_title('', false)) . $after;
        } elseif (is_author()) {
            global $author;
            $userdata = get_userdata($author);
            echo $before . sprintf($text['author'], $userdata->display_name) . $after;
        } elseif (is_404()) {
            echo $before . $text['404'] . $after;
        }

        // Убрано: не выводим номер страницы в хлебных крошках

        echo '</div><!-- .breadcrumbs -->';
    }
}
