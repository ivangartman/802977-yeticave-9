<?php

require_once 'init.php';

//Получение всех категорий
$categories = db_category_all($link);

//Проверяем был ли отправлен запрос "page"
if (isset($_GET['page'])) {
    $page = $_GET['page'];
    $_SESSION['page'] = $page;

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $page = $_SESSION['page'];
    $rate = $_POST;
    $rate['user_id'] = $_SESSION['user']['id'];
    $rate['lot_id'] = $page;
    $errors = [];

    foreach (db_price_max($link, $page) as $price) {
        $price_max = $price['price'];
        $step_rate = $price['step_rate'];
    }
    foreach (db_price($link, $page) as $price_lot) {
        $price_lot = $price_lot['price'];
    }
    if ($price_lot > $price_max) {
        $price_max = $price_lot;
    }
    $min_rate = $price_max + floor(($price_max / 100) * $step_rate);

    if (empty($_POST['price'])) {
        $errors['price'] = 'Сделайте ставку';
    } elseif ((int)($_POST['price']) === 0) {
        $errors['price'] = 'Введите целое число';
    } elseif ($_POST['price'] < $min_rate) {
        $errors['price'] = 'Сделайте ставку не меньше '.price_format($min_rate).' р';
    }

    if (! count($errors)) {
        //---Добавление новой записи в таблицу lots в MySQL---
        $sql = $db_add_rate;
        $data = [
            $rate['user_id'],
            $rate['lot_id'],
            $rate['price'],
        ];
        $res = db_insert($link, $sql, $data);
        if (! $res) {
            $error_message = 'Ставка не добавлена';
            $html = error($title, $categories, $error_message, $user_name);
        }
    }
} else {
    $error_message = 'Данной страницы не существует на сайте';
    $html = error($title, $categories, $error_message, $user_name);
}

foreach (db_price_max($link, $page) as $price) {
    $price_max = $price['price'];
    $step_rate = $price['step_rate'];
}
$min_rate = $price_max + floor(($price_max / 100) * $step_rate);

//Получение id лотов по отправленнному запросу "page"
$id = db_lots_id($link, $page);
if (! $id) {
    $error_message = 'Данной страницы не существует на сайте';
    $html = error($title, $categories, $error_message, $user_name);
} else {
    //Получение содержимого лота по отправленному id
    $lots = db_lots_allid($link, $page);
    foreach ($lots as $title) {
        $title = $title['name_lot'];
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
    ]);
}

$html = include_template('layout.php', [
    'user_name'  => $user_name,
    'title'      => $title,
    'content'    => $page_content,
    'categories' => $categories,
    'main_class' => 'class=" "',
]);
echo $html;
