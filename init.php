<?php

require_once 'helpers.php';
require_once 'functions.php';
require_once 'db.php';


$categories = '';
$lots = null;
$rates = null;
$title = 'YetiCave - Главная страница';
$sum =  null;
$page = null;
$is_auth = rand(0, 1);
$user_name = 'Иван';
date_default_timezone_set("Asia/Novosibirsk");
