<?php

require_once 'init.php';

$categories = db_category_all($link);

$page_content = include_template('login.php', [
    'categories' => $categories
]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {//Проверяем был ли отправлен запрос "POST"
    $login = $_POST;
    $errors = [];

    if (empty($login['password'])) {
        $errors['password'] = 'Введите пароль';
    }
    if (empty($login['email'])) {
        $errors['email'] = 'Введите e-mail';
    } else {
        //Проверяем есть ли такой E-mail в БД
        $email = mysqli_real_escape_string($link, $login['email']);
        $user = db_email($link, $email);
        if ($user) {
            if (password_verify($login['password'], $user['password'])) {
                $_SESSION['user'] = $user;
                $user_name = $_SESSION['user']['name'];
            } else {
                $errors['password'] = 'Неверный пароль';
            }
        } else {
            $errors['email'] = 'Такой пользователь не найден';
        }
    }

    if (count($errors)) {
        $page_content = include_template('login.php', [
            'login'      => $login,
            'errors'     => $errors,
            'categories' => $categories
        ]);
    } else {
        $main_class = 'class="container"';
        header("Location: index.php");
    }
} else {
    $page_content = include_template('login.php', [
        'categories' => $categories
    ]);
}
$html = include_template('layout.php', [
    'user_name'  => $user_name,
    'title'      => $title,
    'content'    => $page_content,
    'categories' => $categories,
    'main_class' => $main_class,
    'pagecat'    => $pagecat
]);
echo $html;
