<?php

/**
 * Добовляет пробел после 3 нулей к цене слота.
 *
 * @param $price int Цена лота
 *
 * @return string
 */
function price_format (float $price): string
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
    $diff=$timeUnix - $now;

    return $diff <= 3600;
}
