<?php

$db = require_once 'config/database.php';
$link = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
mysqli_set_charset($link, "utf8");
if (!$link) {
    $layout_content = error($is_auth, $user_name, $title, $categories);
}

/**
 * Получение всех категорий из таблицы category.
 *
 * @param $link mysqli Ресурс соединения
 *
 * @return array
 */
function db_category_all($link)
{
    $sql = "SELECT * FROM category ORDER BY id ASC";
    $db_category_all = db_fetch_data($link, $sql);

    return $db_category_all;
}

/**
 * Получение содержимого лотов.
 *
 * @param $link mysqli Ресурс соединения
 *
 * @return array
 */
function db_lots_all($link)
{
    $sql = "SELECT l.id, l.name AS name_lot, l.price, l.picture_url, l.date_end, cat.name AS name_cat FROM lots l
            JOIN category cat ON l.category_id = cat.id
            ORDER BY l.id DESC LIMIT 6";
    $db_lots_all = db_fetch_data($link, $sql);

    return $db_lots_all;
}

/**
 * Получение id лотов по отправленнному запросу "page".
 *
 * @param $link mysqli Ресурс соединения
 * @param $page id лота
 *
 * @return array
 */
function db_lots_id($link, $page)
{
    $sql = "SELECT id FROM lots WHERE id = $page";
    $db_lots_id = db_fetch_data($link, $sql);

    return $db_lots_id;
}

/**
 * Получение содержимого лотов по отправленному id.
 *
 * @param $link mysqli Ресурс соединения
 * @param $page id лота
 *
 * @return array
 */
function db_lots_allid($link, $page)
{
    $sql = "SELECT l.name AS name_lot, cat.name AS name_cat, l.content, l.picture_url, l.date_end, r.price AS price_rate, l.price AS price_lot FROM lots l
            JOIN category cat ON l.category_id = cat.id
            LEFT JOIN rates r ON l.id = r.lot_id
            WHERE l.id = $page
            ORDER BY r.price DESC LIMIT 1";
    $db_lots_allid = db_fetch_data($link, $sql);

    return $db_lots_allid;
}

/**
 * Получение переченя ставок по id лота.
 *
 * @param $link mysqli Ресурс соединения
 * @param $page id лота
 *
 * @return array
 */
function db_rate_id($link, $page)
{
    $sql = "SELECT u.name, r.price, r.date_add FROM rates r
            JOIN users u ON r.user_id = u.id
            WHERE r.lot_id = $page
            ORDER BY r.id DESC";
    $db_rate_id = db_fetch_data($link, $sql);

    return $db_rate_id;
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

function db_insert_lot($link, $sql, $data, $is_auth, $user_name, $title, $categories)
{
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

//---Добавление новой записи в таблицу lots---
$db_add_lot = "INSERT INTO lots (user_id, category_id, name, content, picture_url, price, date_end, step_rate)
               VALUES (?, ?, ?, ?, ?, ?, ?, ?)";