<?php

require_once 'init.php';
require_once 'helpers.php';
require_once 'functions.php';
require_once 'config/database.php';

//---Проверяем был ли отправлен запрос "page"
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $layout_content = error($is_auth, $user_name, $title, $categories);
}

//---Получение id лотов по отправленнному запросу "page"---
$id = lots_id($link, $page);
if (!$id) {
    $layout_content = error($is_auth, $user_name, $title, $categories);
} else {
    //---Получение всех категорий---
    $categories = category_all($link);
    //---Получение содержимого лота по отправленному id---
    $lots = lots_allid($link, $page);
    foreach ($lots as $title) {
        $title = $title['name_lot'];
    }
    //---Получение переченя ставок по id лота---
    $rates = rate_id($link, $page);
    $sum = count($rates);//--Подсчёт кол-во ставок

    $page_content = include_template('lot.php', [
        'categories' => $categories,
        'lots' => $lots,
        'rates' => $rates,
        'sum' => $sum
    ]);
}

$layout_content = include_template('layout.php', [
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'title' => $title,
    'content' => $page_content,
    'categories' => $categories,
    'main_class' => 'class=" "'
]);
echo $layout_content;
