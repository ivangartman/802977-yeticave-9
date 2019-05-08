<?php

require_once 'init.php';

//---Получение всех категорий---
$categories = db_category_all($link);

//---Проверяем был ли отправлен запрос "page"
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $error_message = 'Данной страницы не существует на сайте';
    $layout_content = error($title, $categories, $error_message);
}

//---Получение id лотов по отправленнному запросу "page"---
$id = db_lots_id($link, $page);
if (!$id) {
    $error_message = 'Данной страницы не существует на сайте';
    $layout_content = error($title, $categories, $error_message);
} else {
    //---Получение содержимого лота по отправленному id---
    $lots = db_lots_allid($link, $page);
    foreach ($lots as $title) {
        $title = $title['name_lot'];
    }
    //---Получение переченя ставок по id лота---
    $rates = db_rate_id($link, $page);
    $sum = count($rates);//--Подсчёт кол-во ставок

    $page_content = include_template('lot.php', [
        'categories' => $categories,
        'lots' => $lots,
        'rates' => $rates,
        'sum' => $sum
    ]);
}

$layout_content = include_template('layout.php', [
    'title' => $title,
    'content' => $page_content,
    'categories' => $categories,
    'main_class' => 'class=" "'
]);
echo $layout_content;
