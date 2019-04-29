<?php

require_once 'functions.php';
$db = require_once 'config/database.php';

$link = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
mysqli_set_charset($link, "utf8");

$categories = null;
$lots = null;
$rates = null;
$title = 'YetiCave - Главная страница';
$sum =  null;
$page = null;
