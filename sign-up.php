<?php

require_once 'include/init.php';

$categories = db_category_all($link);
if ($user_name) {
    $error_message = 'Вы уже зарегестрировались';
    $html = error($title, $categories, $error_message, $user_name, $pagecat, $search);
    exit();
} else {
    $page_content = include_template('sign-up.php', [
        'categories' => $categories
    ]);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user = $_POST;
        $required = ['name', 'password', 'contact'];
        $errors = [];
        $error_massage = [
            'password' => 'Введите пароль',
            'name'     => 'Введите имя',
            'contact'  => 'Напишите как с вами связаться'
        ];
        //Валидация пароля, имени, контактов
        foreach ($required as $key) {
            if (empty($_POST[$key])) {
                $errors[$key] = $error_massage[$key];
            }
        }
        //Валидация имени
        if (empty($_POST['name'])) {
            $errors['name'] = 'Введите имя';
        } elseif (mb_strlen($_POST['name']) > 60) {
            $errors['name'] = 'Введите не более 60 символов';
        }
        //Валидация пароля
        if (empty($_POST['password'])) {
            $errors['password'] = 'Введите пароль';
        } elseif (mb_strlen($_POST['password']) > 20) {
            $errors['password'] = 'Введите не более 20 символов';
        }
        //Валидация контактных данных
        if (empty($_POST['contact'])) {
            $errors['contact'] = 'Напишите как с вами связаться';
        } elseif (mb_strlen($_POST['password']) > 120) {
            $errors['contact'] = 'Введите не более 120 символов';
        }
        //Валидация e-mail
        if (empty($_POST['email'])) {
            $errors['email'] = 'Введите e-mail';
        } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Введите коректный e-mail';
        } elseif (count(db_user_email($link, $_POST['email'])) > 0) {
            $errors['email'] = 'Пользователь с этим e-mail уже зарегистрирован';
        }

        if (count($errors)) {
            $page_content = include_template('sign-up.php', [
                'user'       => $user,
                'errors'     => $errors,
                'categories' => $categories
            ]);
        } else {
            //Добавление новой записи в таблицу users в MySQL
            $user['password'] = password_hash($_POST['password'],
                PASSWORD_DEFAULT);
            $sql = $db_add_user;
            $data = [
                $user['email'],
                $user['password'],
                $user['name'],
                $user['contact']
            ];
            $res = db_insert($link, $sql, $data);
            if ($res) {
                header("Location: login.php");
            } else {
                $error_message = 'Новый пользователь не зарегестрирован';
                $html = error($title, $categories, $error_message, $user_name,
                    $pagecat, $search);
            }
        }
    } else {
        $page_content = include_template('sign-up.php', [
            'categories' => $categories
        ]);
    }
}
$html = include_template('layout.php', [
    'user_name'  => $user_name,
    'title'      => $title,
    'content'    => $page_content,
    'categories' => $categories,
    'main_class' => $main_class,
    'pagecat'    => $pagecat,
    'search'    => $search
]);
echo $html;
