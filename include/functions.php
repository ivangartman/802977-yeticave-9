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
    $price = number_format($price, 0, '', ' ') . ' р';

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
 * Проверяем время до закрытия лота.
 *
 * @param string $date_end Дата окончания лота
 *
 * @return bool
 */
function endDate($date_end)
{
    $timeUnix = strtotime($date_end);
    $now = time();
    $diff = $timeUnix - $now;
    if ($diff <= 0) {
        return $diff;
    }
}

/**
 * Выводим страницу с сообщением об ошибке.
 *
 * @param string $title         Название страницы
 * @param string $categories    Название категории
 * @param string $error_message Текст сообщения об ощибке
 *
 * @return string
 */
function error($title, $categories, $error_message, $user_name, $pagecat, $search)
{
    $page_content = include_template('error.php', [
        'error_message' => $error_message,
        'categories'    => $categories,
        'pagecat'       => $pagecat
    ]);
    $html = include_template('layout.php', [
        'user_name'  => $user_name,
        'title'      => $title,
        'content'    => $page_content,
        'categories' => $categories,
        'pagecat'    => $pagecat,
        'search'     => $search
    ]);
    http_response_code(404);
    echo $html;

    die;

}

/**
 * Преобразуем дату добавления ставки.
 *
 * @param array $data Массив с данными
 *
 * @return array
 */
function date_rate($data)
{
    $lots = [];
    foreach ($data as $lot) {
        $time = time() - strtotime($lot['date_add']);
        if ($time < 60) {
            $lot['date_add'] = $time . get_noun_plural_form($time, ' секунду',' секунды', ' секунд') . ' назад';
            $lots[] = $lot;
        } elseif ($time < 3600) {
            $lot['date_add'] = floor($time / 60) . get_noun_plural_form(floor($time / 60), ' минуту', ' минуты',' минут') . ' назад';
            $lots[] = $lot;
        } elseif ($time < 86400) {
            $lot['date_add'] = floor($time / 3600) . get_noun_plural_form(floor($time / 3600), ' час', ' часа',' часов') . ' назад';
            $lots[] = $lot;
        } else {
            $lot['date_add'] = floor($time / 86400) . get_noun_plural_form(floor($time / 86400), ' день', ' дня',' дней') . ' назад';
            $lots[] = $lot;
        }
    }

    return $lots;
}

/**
 * Определение максимальной ставки лота.
 *
 * @param mysqli $link Ресурс соединения
 * @param int    $page id лота
 *
 * @return int
 */
function minrate($link, $page)
{
    $price_max = '';
    foreach (db_price_max($link, $page) as $price) {
        if (isset($price['price'])) {
            $price_max = $price['price'];
        }
    }
    foreach (db_price($link, $page) as $price_lots) {
        $price_lot = $price_lots['price'];
        $step_rate = $price_lots['step_rate'];
    }
    if ($price_lot > $price_max) {
        $price_max = $price_lot;
    }
    $min_rate = $price_max + floor(($price_max / 100) * $step_rate);

    return $min_rate;
}
