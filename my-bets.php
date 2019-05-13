<?php

require_once 'init.php';

$categories = db_category_all($link);
$lots = db_lots_all($link);

$page_content = include_template('my-bets.php', [
    'categories' => $categories,
]);
$user_id = $_SESSION['user']['id'];
$lots_user = db_lots($link, $user_id);

if (! $lots_user) {
    $error_message = 'У вас нет ставок. Сделайте ставку';
    $html = error($title, $categories, $error_message, $user_name);
} else {
    $lots_user = date_rate($lots_user);

    $page_content = include_template('my-bets.php', [
        'categories' => $categories,
        'lots_user'  => $lots_user,
        'user_id'    => $user_id,
        'link'       => $link,
    ]);
}

$html = include_template('layout.php', [
    'user_name'  => $user_name,
    'title'      => $title,
    'content'    => $page_content,
    'categories' => $categories,
    'main_class' => 'class=" "',
]);
echo $html;
