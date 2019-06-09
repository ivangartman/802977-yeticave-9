<?php

require_once 'include/init.php';

$categories = db_category_all($link);
$rate = '';
$errors = '';
if (isset($_GET['page']) and filter_var(trim($_GET['page']), FILTER_VALIDATE_INT) ) {
    $page = trim($_GET['page']);
    $_SESSION['page'] = $page;
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (! isset($_SESSION['page'])) {
        $error_message = 'Данной страницы не существует на сайте';
        $html = error($title, $categories, $error_message, $user_name, $pagecat, $search);
    }
    $page = $_SESSION['page'];
    $rate = $_POST;
    $rate['user_id'] = $user_id;
    $rate['lot_id'] = $page;
    $errors = [];
    if (! filter_var($rate['lot_id'], FILTER_VALIDATE_INT)) {
        $error_message = 'Данной страницы не существует на сайте';
        $html = error($title, $categories, $error_message, $user_name, $pagecat, $search);
    }
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
    $min_rate = minrate($link, $page);

    //Валидация ставки
    if (isset($_POST['price']) and empty(trim($_POST['price']))) {
        $errors['price'] = 'Сделайте ставку';
    } elseif (! filter_var(trim($_POST['price']), FILTER_VALIDATE_INT)){
        $errors['price'] = 'Введите целое число больше 0';
    } elseif (trim($_POST['price']) < $min_rate) {
        $errors['price'] = 'Сделайте ставку не меньше '.price_format($min_rate);
    } elseif (! $user_name and ! $rate['user_id']) {
        $errors['price'] = 'Вы не зарегестрированны';
    } elseif (count(db_lots_id($link, $page)) === 0) {
        $errors['price'] = 'Такого лота не существует';
    } elseif ($user_id == $lot_userid) {
        $errors['price'] = 'Вы неможите сделать ставку у собственного лота';
    } elseif ($date_end) {
        $errors['price'] = 'Дата публикации лота истекла';
    } elseif ($user_id == $rate_userid) {
        $errors['price'] = 'Нельзя делать 2 ставки подряд';
    } elseif (! filter_var($rate['user_id'], FILTER_VALIDATE_INT)) {
        $errors['price'] = 'Некоректные данные пользователя';
    }

    if (! count($errors)) {
        //---Добавление новой записи в таблицу lots в MySQL---
        $sql = $db_add_rate;
        $data = [
            trim($rate['user_id']),
            trim($rate['lot_id']),
            trim($rate['price'])
        ];
        $res = db_insert($link, $sql, $data);
        if (! $res) {
            $error_message = 'Ставка не добавлена';
            $html = error($title, $categories, $error_message, $user_name, $pagecat, $search);
        }
    }
} else {
    $error_message = 'Данной страницы не существует на сайте';
    $html = error($title, $categories, $error_message, $user_name, $pagecat, $search);
}

//Получение id лотов по отправленнному запросу "page"
$id = db_lots_id($link, $page);
if (! $id) {
    $error_message = 'Данной страницы не существует на сайте';
    $html = error($title, $categories, $error_message, $user_name, $pagecat, $search);
} else {
    $min_rate = minrate($link, $page);
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
    if (strtotime($lot['date_end']) < time()) {
        $error_message = 'Данной страницы не существует на сайте';
        $html = error($title, $categories, $error_message, $user_name, $pagecat, $search);
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
    'main_class' => $main_class,
    'pagecat'    => $pagecat,
    'search'    => $search
]);
echo $html;
