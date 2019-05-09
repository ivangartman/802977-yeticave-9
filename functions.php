<?php

/**
 * Добовляет пробел после 3 нулей к цене слота.
 *
 * @param int $price Цена лота
 *
 * @return string
 */
function price_format(float $price): string
{
    $price = ceil($price);
    $price = number_format($price, 0, '', ' ');

    return $price;
}

/**
 * Выводим оставшееся время до полуночи.
 *
 * @param string $date_end Дата окончания лота
 *
 * @return string
 */
function timer($date_end)
{
    $now = strtotime('now');
    $midninght = strtotime($date_end);
    $diff = $midninght - $now;
    $hours = floor($diff / 3600);
    $minutes = floor(($diff % 3600) / 60);
    $formatDate = $hours . ':' . $minutes;
    return $formatDate;
}

/**
 * Проверяем осталось меньше часа или нет до данного времени.
 *
 * @param string $date_end Дата окончания лота
 *
 * @return bool
 */
function timer_finishing($date_end)
{
    $timeUnix = strtotime($date_end);
    $now = time();
    $diff = $timeUnix - $now;

    return $diff <= 3600;
}

/**
 * Выводим страницу с сообщением об ошибке.
 *
 * @param string $title Название страницы
 * @param string $categories Название категории
 * @param string $error_message Текст сообщения об ощибке
 *
 * @return array
 */
function error($title, $categories, $error_message, $user_name)
{
    $page_content = include_template('error.php', [
        'error_message' => $error_message
    ]);
    $html = include_template('layout.php', [
        'user_name' => $user_name,
        'title' => $title,
        'content' => $page_content,
        'categories' => $categories
    ]);
    echo $html;
    die;
    return $html;
}
