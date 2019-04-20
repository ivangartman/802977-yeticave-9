<?php

require_once 'helpers.php';
$is_auth = rand(0, 1);
$user_name = 'Иван'; // укажите здесь ваше имя
$categories = ['Доски и лыжи', 'Крепления', 'Ботинки', 'Одежда', 'Инструменты', 'Разное'];
$lots = [
    [
        'name' => '2014 Rossignol District Snowboard',
        'category' => 'Доски и лыжи',
        'price' => 10999,
        'picture_url' => 'img/lot-1.jpg',
        'time' => 'tomorrow'
    ],
    [
        'name' => 'DC Ply Mens 2016/2017 Snowboard',
        'category' => 'Доски и лыжи',
        'price' => 159999,
        'picture_url' => 'img/lot-2.jpg',
        'time' => 'tomorrow'
    ],
    [
        'name' => 'Крепления Union Contact Pro 2015 года размер L/X',
        'category' => 'Крепления',
        'price' => 8000,
        'picture_url' => 'img/lot-3.jpg',
        'time' => 'tomorrow'
    ],
    [
        'name' => 'Ботинки для сноуборда DC Mutiny Charocal',
        'category' => 'Ботинки',
        'price' => 10999,
        'picture_url' => 'img/lot-4.jpg',
        'time' => 'tomorrow'
    ],
    [
        'name' => 'Куртка для сноуборда DC Mutiny Charocal',
        'category' => 'Одежда',
        'price' => 7500,
        'picture_url' => 'img/lot-5.jpg',
        'time' => 'tomorrow'
    ],
    [
        'name' => 'Маска Oakley Canopy',
        'category' => 'Разное',
        'price' => 5400,
        'picture_url' => 'img/lot-6.jpg',
        'time' => 'tomorrow'
    ]
];
function price_format (float $price): string
{
    $price = ceil($price);
    $price = number_format($price, 0, '', ' ');

    return $price;
}
date_default_timezone_set("Asia/Novosibirsk");
function timer($time)
{
    $now = date_create();
    $time = date_create($time);
    $interval = date_diff($now, $time);

    return $interval->format('%H:%I');
}
function timer_finishing($time)
{
    $time = strtotime($time);
    if (($time - time()) <= 3600) {

        return true;
    } else {

        return false;
    }
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