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
function timer()
{
    $now = strtotime('now');
    $midninght = strtotime('tomorrow');
    $diff = $midninght - $now;
    return date('H:i', $midninght + $diff);
}

/**
 * Проверяем осталось меньше часа или нет до данного времени.
 *
 * @return bool
 */
function timer_finishing()
{
    $timeUnix = strtotime('tomorrow');
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
