<?php

$link = mysqli_connect('localhost', 'root', '', 'yeticave');
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
function category_all($link)
{
    $sql = "SELECT * FROM category ORDER BY id ASC";
    $category_all = db_fetch_data($link, $sql);
    return $category_all;
}

/**
 * Получение содержимого лотов.
 *
 * @param $link mysqli Ресурс соединения
 *
 * @return array
 */
function lots_all($link)
{
    $sql = "SELECT l.id, l.name AS name_lot, l.price, l.picture_url, l.date_end, cat.name AS name_cat FROM lots l
            JOIN category cat ON l.category_id = cat.id
            ORDER BY l.id DESC LIMIT 6";
    $lots_all = db_fetch_data($link, $sql);
    return $lots_all;
}

/**
 * Получение id лотов по отправленнному запросу "page".
 *
 * @param $link mysqli Ресурс соединения
 *
 * @return array
 */
function lots_id($link, $page)
{
    $sql = "SELECT id FROM lots WHERE id = $page";
    $lots_id = db_fetch_data($link, $sql);
    return $lots_id;
}

/**
 * Получение содержимого лотов по отправленному id.
 *
 * @param $link mysqli Ресурс соединения
 *
 * @return array
 */
function lots_allid($link, $page)
{
    $sql = "SELECT l.name AS name_lot, cat.name AS name_cat, l.content, l.picture_url, l.date_end, r.price AS price_rate, l.price AS price_lot FROM lots l            
            JOIN category cat ON l.category_id = cat.id                        
            LEFT JOIN rates r ON l.id = r.lot_id
            WHERE l.id = $page
            ORDER BY r.price DESC LIMIT 1";
    $lots_allid = db_fetch_data($link, $sql);
    return $lots_allid;
}

/**
 * Получение переченя ставок по id лота.
 *
 * @param $link mysqli Ресурс соединения
 *
 * @return array
 */
function rate_id($link, $page)
{
    $sql = "SELECT u.name, r.price, r.date_add FROM rates r
            JOIN users u ON r.user_id = u.id                                                          
            WHERE r.lot_id = $page            
            ORDER BY r.id DESC";
    $rate_id = db_fetch_data($link, $sql);
    return $rate_id;
}

//---Добавление новой записи в таблицу lots---
$add_lot = "INSERT INTO lots (user_id, category_id, name, content, picture_url, price, date_end, step_rate) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";