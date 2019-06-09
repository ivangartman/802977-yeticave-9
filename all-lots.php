<?php

require_once 'include/init.php';

$categories = db_category_all($link);
if (isset($_GET['pagecat']) and filter_var(trim($_GET['pagecat']), FILTER_VALIDATE_INT)) {
    $pagecat = trim($_GET['pagecat']);
    $lots = db_lotscat($link, $pagecat);
    if (!$lots) {
        $error_message = 'В данной категории отсутствуют лоты';
        $html = error($title, $categories, $error_message, $user_name, $pagecat, $search);
    } else {
        foreach ($lots as $key) {
            $catname = $key['name_cat'];
        }
        if (isset($_GET['page'])) {
            if (filter_var(trim($_GET['page']), FILTER_VALIDATE_INT)) {
                $cur_page = trim($_GET['page']);
            } else {
                $cur_page = 1;
            }
        } else {
            $cur_page = 1;
        }
            $page_items = 9;
            $cnt = count($lots);
            $pages_count = ceil($cnt / $page_items);
            $offset = ($cur_page - 1) * $page_items;
            $pages = range(1, $pages_count);

            $lots = db_lotscat_page($link, $pagecat, $page_items, $offset);
    }
} else {
    $error_message = 'В данной категории отсутствуют лоты';
    $html = error($title, $categories, $error_message, $user_name, $pagecat, $search);
}

$page_content = include_template('all-lots.php', [
    'categories'  => $categories,
    'lots'        => $lots,
    'pagecat'     => $pagecat,
    'pages'       => $pages,
    'pages_count' => $pages_count,
    'cur_page'    => $cur_page,
    'catname'     => $catname
]);
$html = include_template('layout.php', [
    'user_name'  => $user_name,
    'title'      => $title,
    'content'    => $page_content,
    'categories' => $categories,
    'main_class' => $main_class,
    'pagecat'    => $pagecat,
    'search'    => $search
]);
echo $html;
