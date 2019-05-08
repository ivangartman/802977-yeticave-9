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
 * @param mysqli $link Ресурс соединения
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
 * @param mysqli $link Ресурс соединения
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
 * @param int $page id лота
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
 * @param mysqli $link Ресурс соединения
 * @param int $page id лота
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
 * @param mysqli $link Ресурс соединения
 * @param int $page id лота
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
 * Получение записей с таблиц в MySQL.
 *
 * @param mysqli $link Ресурс соединения
 * @param string $sql SQL запрос с плейсхолдерами вместо значений
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

//---Добавление новой записи в таблицу lots---
$db_add_lot = "INSERT INTO lots (user_id, category_id, name, content, picture_url, price, date_end, step_rate)
               VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

/**
 * Добавление новой записи в таблицу lots в MySQL.
 *
 * @param mysqli $link Ресурс соединения
 * @param string $sql SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return array
 */

function db_insert_lot($link, $sql, $data)
{
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    $result = mysqli_stmt_execute($stmt);

    return $result;
}

/**
 * Проверяем есть ли уже такой e-mail в БД.
 *
 * @param mysqli $link Ресурс соединения
 * @param string $email E-mail пользователя
 *
 * @return array
 */
function db_user_email($link, $email)
{
    $sql = "SELECT id FROM users WHERE email = '$email'";
    $user_email = db_fetch_data($link, $sql);

    return $user_email;
}

//---Добавление новой записи в таблицу users---
$db_add_user = "INSERT INTO users (email, password, name, contact) 
             VALUES (?, ?, ?, ?)";

/**
 * Добавление новой записи в таблицу users в MySQL.
 *
 * @param mysqli $link Ресурс соединения
 * @param string $sql SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return array
 */

function db_insert_user($link, $sql, $data)
{
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    $res = mysqli_stmt_execute($stmt);

    return $res;
}

/**
 * Получение переченя ставок по id лота.
 *
 * @param string $email E-mail пользователя
 *
 * @return array
 */
function db_email($email)
{
    $sql = "SELECT * FROM users WHERE email = '$email'";

    return $sql;
}
