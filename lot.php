<?php

require_once 'helpers.php';
require_once 'functions.php';
require_once 'init.php';

$is_auth = rand(0, 1);

$user_name = 'Иван';

if (isset($_GET['page'])) {
    $page = $_GET['page'];
}

if (!$link || !$page) {
    $page_content = include_template('404.php');
} else {
    //---Делаем поиск по полученному id, если id не получен то делаем ссылку на струницу 404.php---
    $sql = "SELECT id FROM lots WHERE id = $page";
    $id = db_fetch_data($link, $sql);
    if (!$id) {
        $page_content = include_template('404.php', []);
    } else {
        //---Получаем все категории---
        $sql = "SELECT * FROM category ORDER BY id ASC";
        $categories = db_fetch_data($link, $sql);

        //---Получаем содержимое лота по id---
        $sql = "SELECT l.name AS name_lot, cat.name AS name_cat, l.content, l.picture_url, l.date_end, r.price AS price_rate, l.price AS price_lot FROM lots l            
            JOIN category cat ON l.category_id = cat.id                        
            LEFT JOIN rates r ON l.id = r.lot_id
            WHERE l.id = $page
            ORDER BY r.price DESC LIMIT 1";
        $lots = db_fetch_data($link, $sql);
        foreach ($lots as $title) {
            $title = $title['name_lot'];
        }

        //---Получаем перечень ставок по id лота---
        $sql = "SELECT u.name, r.price, r.date_add FROM rates r
            JOIN users u ON r.user_id = u.id                                                          
            WHERE r.lot_id = $page            
            ORDER BY r.id DESC";
        $rates = db_fetch_data($link, $sql);
        $sum = count($rates);//--Считаем кол-во ставок

        $page_content = include_template('lot.php', [
            'categories' => $categories,
            'lots' => $lots,
            'rates' => $rates,
            'sum' => $sum
        ]);
    }
}

$layout_content = include_template('layout.php', [
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'title' => $title,
    'content' => $page_content,
    'categories' => $categories
]);
echo $layout_content;
