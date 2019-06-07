<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


session_start();

require_once 'helpers.php';
require_once 'functions.php';
require_once 'db.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../getwinner.php';

$categories = null;
$lots = null;
$rates = null;
$title = 'YetiCave - Главная страница';
$sum = null;
$page = null;
$main_class = '';
$pagecat = '';
$user_name = $_SESSION['user']['name'] ?? '';
$user_id = $_SESSION['user']['id'] ?? '';
$search = trim($_GET['search'] ?? '');
date_default_timezone_set("Asia/Novosibirsk");
