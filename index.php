<?php

require_once 'init.php';
require_once 'helpers.php';
require_once 'functions.php';
require_once 'config/database.php';

$categories = category_all($link);
$lots = lots_all($link);

$page_content = include_template('index.php', [
    'categories' => $categories,
    'lots' => $lots
]);
$layout_content = include_template('layout.php', [
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'title' => $title,
    'content' => $page_content,
    'categories' => $categories,
    'main_class' => 'class="container"'
]);
echo $layout_content;
