<?php

/**
 * Добовляет пробел после 3 нулей к цене слота.
 *
 * @param $price int Цена лота
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
 * Получение записей с таблиц в MySQL
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return array
 */
function db_fetch_data($link, $sql, $data = [])
{
    $result = [];
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($res) {
        $result = mysqli_fetch_all($res, MYSQLI_ASSOC);
    }

    return $result;
}

/**
 * Выводим страницу с сообщением об ошибке.
 *
 * @param $is_auth int Случайное число 0 или 1
 * @param $user_name string Имя пользователя
 * @param $title string Название страницы
 * @param $categories string Название категории
 *
 * @return array
 */
function error($is_auth, $user_name, $title, $categories)
{
    $page_content = include_template('error.php');
    $layout_content = include_template('layout.php', [
        'is_auth' => $is_auth,
        'user_name' => $user_name,
        'title' => $title,
        'content' => $page_content,
        'categories' => $categories
    ]);
    echo $layout_content;
    die;
    return $layout_content;
}

/**
 * Добавление новой записи в таблицу lots в MySQL.
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 * @param $is_auth int Случайное число 0 или 1
 * @param $user_name string Имя пользователя
 * @param $title string Название страницы
 * @param $categories string Название категории
 *
 * @return array
 */

function db_insert_lot($link, $sql, $data, $is_auth, $user_name, $title, $categories) {
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    $res = mysqli_stmt_execute($stmt);
    if ($res) {
        $lot_id = mysqli_insert_id($link);
        $layout_content = header("Location: lot.php?page=" . $lot_id);
    } else {
        $layout_content = error($is_auth, $user_name, $title, $categories);
    }

    return $layout_content;
}
