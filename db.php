<?php

$db = require_once 'config/database.php';
$link = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
mysqli_set_charset($link, "utf8");
if (!$link) {
    $html = error($is_auth, $user_name, $title, $categories);
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
            WHERE l.date_end > NOW()
            ORDER BY l.id DESC LIMIT 9";
    $db_lots_all = db_fetch_data($link, $sql);

    return $db_lots_all;
}

/**
 * Получение id лотов по отправленнному запросу "page".
 *
 * @param mysqli $link Ресурс соединения
 * @param int    $page id лота
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
 * @param int    $page id лота
 *
 * @return array
 */
function db_lots_allid($link, $page)
{
    $sql = "SELECT l.name AS name_lot, cat.name AS name_cat, l.user_id, l.content, l.picture_url, l.date_end, r.price AS price_rate, l.price AS price_lot, r.user_id AS rate_userid FROM lots l
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
 * @param int    $page id лота
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
 * @param string $sql  SQL запрос с плейсхолдерами вместо значений
 * @param array  $data Данные для вставки на место плейсхолдеров
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
 * @param string $sql  SQL запрос с плейсхолдерами вместо значений
 * @param array  $data Данные для вставки на место плейсхолдеров
 *
 * @return boolean
 */

function db_insert($link, $sql, $data)
{
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    $result = mysqli_stmt_execute($stmt);

    return $result;
}

/**
 * Проверяем есть ли уже такой e-mail в БД.
 *
 * @param mysqli $link  Ресурс соединения
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
 * Получение переченя ставок по id лота.
 *
 * @param mysqli $link  Ресурс соединения
 * @param string $email E-mail пользователя
 *
 * @return array
 */
function db_email($link, $email)
{
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $res = mysqli_query($link, $sql);
    $user = $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null;

    return $user;
}

//Добавление новой ставки в таблицу rates
$db_add_rate = "INSERT INTO rates (user_id, lot_id, price)
                VALUES (?, ?, ?)";

/**
 * Получение максимальной ставки.
 *
 * @param mysqli $link Ресурс соединения
 * @param int    $page id лота
 *
 * @return array
 */
function db_price_max($link, $page)
{
    $sql = "SELECT r.price, r.user_id, l.step_rate FROM rates r
            JOIN lots l ON r.lot_id = l.id
            WHERE r.lot_id = $page
            ORDER BY r.price DESC LIMIT 1";
    $price_max = db_fetch_data($link, $sql);

    return $price_max;
}

/**
 * Получение стоимости лота.
 *
 * @param mysqli $link Ресурс соединения
 * @param int    $page id лота
 *
 * @return array
 */
function db_price($link, $page)
{
    $sql = "SELECT price FROM lots WHERE id = $page";
    $price = db_fetch_data($link, $sql);

    return $price;
}

/**
 * Получение переченя лотов пользователя по id лота.
 *
 * @param mysqli $link    Ресурс соединения
 * @param int    $user_id id пользователя
 *
 * @return array
 */
function db_lots($link, $user_id)
{
    $sql = "SELECT l.name AS name_lot, cat.name AS name_cat, l.picture_url, l.date_end, r.price, r.date_add, l.id, r.user_id, u.contact FROM rates r
            JOIN lots l ON r.lot_id = l.id 
            JOIN users u ON r.user_id = u.id
            JOIN category cat ON l.category_id = cat.id
            WHERE r.user_id = $user_id                          
            ORDER BY r.id DESC";
    $db_lots = db_fetch_data($link, $sql);

    return $db_lots;
}

/**
 * Полнотекстовый поиск по наименованию и описанию лота.
 *
 * @param mysqli $link   Ресурс соединения
 * @param string $search Выражение по которому идёт поиск
 *
 * @return array
 */
function db_lots_search($link, $search)
{
    $sql = "SELECT cat.name AS name_cat, l.name AS name_lot, l.id, l.price, l.picture_url, l.date_end FROM lots l
            JOIN category cat ON l.category_id = cat.id
            WHERE MATCH(l.name, l.content) AGAINST('$search*' IN BOOLEAN MODE)";
    $db_lots_search = db_fetch_data($link, $sql);

    return $db_lots_search;
}

/**
 * Полнотекстовый поиск по наименованию и описанию лота + плагинация.
 *
 * @param mysqli $link       Ресурс соединения
 * @param string $search     Выражение по которому идёт поиск
 * @param int    $page_items Количество выводимых записей
 * @param int    $offset     Смещение выборки
 *
 * @return array
 */
function db_lots_search_page($link, $search, $page_items, $offset)
{
    $sql = "SELECT cat.name AS name_cat, l.name AS name_lot, l.id, l.price, l.picture_url, l.date_end FROM lots l
            JOIN category cat ON l.category_id = cat.id
            WHERE MATCH(l.name, l.content) AGAINST('$search*' IN BOOLEAN MODE)
            ORDER BY l.id DESC LIMIT $page_items OFFSET $offset";
    $db_lots_search_page = db_fetch_data($link, $sql);

    return $db_lots_search_page;
}

/**
 * Получение закрытых лотов без победителей.
 *
 * @param mysqli $link Ресурс соединения
 *
 * @return array
 */
function db_endDate_lot($link)
{
    $sql = "SELECT id, name FROM lots        
            WHERE date_end <= NOW() AND ISNULL(winner_id)";
    $endDate_lot = db_fetch_data($link, $sql);

    return $endDate_lot;
}

/**
 * Поиск победителя.
 *
 * @param mysqli $link   Ресурс соединения
 * @param int    $lot_id id закрытого лота
 *
 * @return array
 */
function db_winnerUser($link, $lot_id)
{
    $sql = "SELECT r.user_id, u.email, u.name FROM rates r
            JOIN lots l ON r.lot_id = $lot_id
            JOIN users u ON u.id = r.user_id
            ORDER BY r.price DESC LIMIT 1";
    $winnerUser = db_fetch_data($link, $sql);

    return $winnerUser;
}

//Добавление победителя в таблицу с лотами
$db_add_winner = "UPDATE lots SET winner_id = (?) WHERE id = (?)";

/**
 * Получение содержимого лотов.
 *
 * @param mysqli $link    Ресурс соединения
 * @param int    $pagecat id категории
 *
 * @return array
 */
function db_lotscat($link, $pagecat)
{
    $sql = "SELECT l.id, l.name AS name_lot, l.price, l.picture_url, l.date_end, cat.name AS name_cat FROM lots l
            JOIN category cat ON l.category_id = cat.id
            WHERE cat.id = $pagecat AND l.date_end > NOW()
            ORDER BY l.id DESC";
    $db_lotscat = db_fetch_data($link, $sql);

    return $db_lotscat;
}

/**
 * Полнотекстовый поиск по наименованию и описанию лота + плагинация.
 *
 * @param mysqli $link       Ресурс соединения
 * @param string $pagecat    id активного лота
 * @param int    $page_items Количество выводимых записей
 * @param int    $offset     Смещение выборки
 *
 * @return array
 */
function db_lotscat_page($link, $pagecat, $page_items, $offset)
{
    $sql = "SELECT l.id, l.name AS name_lot, l.price, l.picture_url, l.date_end, cat.name AS name_cat FROM lots l
            JOIN category cat ON l.category_id = cat.id
            WHERE cat.id = $pagecat AND l.date_end > NOW()
            ORDER BY l.id DESC LIMIT $page_items OFFSET $offset";
    $db_lotscat_page = db_fetch_data($link, $sql);

    return $db_lotscat_page;
}
