<?php

require_once 'init.php';

//Получение всех категорий
$categories = db_category_all($link);

//Проверяем был ли отправлен запрос "page"
if (isset($_GET['page'])) {
    $page = $_GET['page'];
    $_SESSION['page'] = $page;
    $min_rate = minrate($link, $page);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $page = $_SESSION['page'];
    $rate = $_POST;
    $rate['user_id'] = $_SESSION['user']['id'];
    $rate['lot_id'] = $page;
    $errors = [];

    $min_rate = minrate($link, $page);

    if (empty($_POST['price'])) {
        $errors['price'] = 'Сделайте ставку';
    } elseif ((int)($_POST['price']) === 0) {
        $errors['price'] = 'Введите целое число';
    } elseif ($_POST['price'] < $min_rate) {
        $errors['price'] = 'Сделайте ставку не меньше '.price_format($min_rate);
    }

    if (! count($errors)) {
        //---Добавление новой записи в таблицу lots в MySQL---
        $sql = $db_add_rate;
        $data = [
            $rate['user_id'],
            $rate['lot_id'],
            $rate['price']
        ];
        $res = db_insert($link, $sql, $data);
        if (! $res) {
            $error_message = 'Ставка не добавлена';
            $html = error($title, $categories, $error_message, $user_name, $pagecat);
        }
    }
} else {
    $error_message = 'Данной страницы не существует на сайте';
    $html = error($title, $categories, $error_message, $user_name, $pagecat);
}

//Получение id лотов по отправленнному запросу "page"
$id = db_lots_id($link, $page);
if (! $id) {
    $error_message = 'Данной страницы не существует на сайте';
    $html = error($title, $categories, $error_message, $user_name, $pagecat);
} else {
    //Получение содержимого лота по отправленному id
    $lots = db_lots_allid($link, $page);
    $date_end = NULL;
    foreach ($lots as $lot) {
        $title = $lot['name_lot'];
        if (strtotime($lot['date_end']) < time()) {
        $date_end = ' ';
        }
        $lot_userid =  $lot['user_id'];
        $rate_userid = $lot['rate_userid'];
    }
    //Получение переченя ставок по id лота
    $rates = db_rate_id($link, $page);
    $sum = count($rates);//Подсчёт кол-во ставок
    $rates = date_rate($rates);//Преобразуем дату добавления ставки

    $page_content = include_template('lot.php', [
        'user_name'  => $user_name,
        'categories' => $categories,
        'lots'       => $lots,
        'rates'      => $rates,
        'sum'        => $sum,
        'rate'       => $rate,
        'errors'     => $errors,
        'min_rate'   => $min_rate,
        'date_end'   => $date_end,
        'user_id'    => $user_id,
        'lot_userid' => $lot_userid,
        'rate_userid' => $rate_userid

    ]);
}

$html = include_template('layout.php', [
    'user_name'  => $user_name,
    'title'      => $title,
    'content'    => $page_content,
    'categories' => $categories,
    'main_class' => 'class=" "',
    'pagecat'    => $pagecat
]);
echo $html;
