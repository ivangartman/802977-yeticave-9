<?php

require_once 'helpers.php';
require_once 'functions.php';
require_once 'init.php';

date_default_timezone_set("Asia/Novosibirsk");

$is_auth = rand(0, 1);

$user_name = 'Иван'; // укажите здесь ваше имя

if (!$link) {
    $error = "Ошибка подключения: " . mysqli_connect_error();
    echo $error;
} else {
    $sql = "SELECT * FROM category ORDER BY id ASC";
    $categories = db_fetch_data($link, $sql);

    $sql = "SELECT l.name_lot, l.price, l.picture_url, cat.name FROM lots l
            JOIN category cat ON l.category_id = cat.id
            ORDER BY l.id DESC";
    $lots = db_fetch_data($link, $sql);
}

$page_content = include_template('index.php', [
    'categories' => $categories,
    'lots' => $lots
]);
$layout_content = include_template('layout.php', [
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'title' => 'YetiCave - Главная страница',
    'content' => $page_content,
    'categories' => $categories
]);
echo $layout_content;
