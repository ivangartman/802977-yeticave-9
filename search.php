<?php

require_once 'include/init.php';
$pages = '';
$pages_count = '';
$cur_page = '';
if ($search) {
    $lots = db_lots_search($link, $search);

    if (isset($_GET['page'])) {
        if ((int)$_GET['page'] != 0) {
            $cur_page = $_GET['page'];
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

    $lots = db_lots_search_page($link, $search, $page_items, $offset);
}

$categories = db_category_all($link);
$page_content = include_template('search.php', [
    'categories'  => $categories,
    'lots'        => $lots,
    'search'      => $search,
    'pages'       => $pages,
    'pages_count' => $pages_count,
    'cur_page'    => $cur_page
]);

$html = include_template('layout.php', [
    'user_name'  => $user_name,
    'title'      => $title,
    'content'    => $page_content,
    'categories' => $categories,
    'main_class' => $main_class,
    'search'     => $search,
    'pagecat'    => $pagecat
]);
echo $html;
