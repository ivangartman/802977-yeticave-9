<?php

session_start();

require_once 'helpers.php';
require_once 'functions.php';
require_once 'db.php';
require_once 'vendor/autoload.php';
require_once 'getwinner.php';

$categories = '';
$lots = null;
$rates = null;
$title = 'YetiCave - Главная страница';
$sum = null;
$page = null;
$main_class = " ";
$user_name = $_SESSION['user']['name'];
$user_id = $_SESSION['user']['id'];
$pagecat = '';

date_default_timezone_set("Asia/Novosibirsk");
