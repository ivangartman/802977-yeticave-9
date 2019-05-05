<?php

require_once 'init.php';

$categories = db_category_all($link);
$lots = db_lots_all($link);

$page_content = include_template('sign-up.php', [
    'categories' => $categories,
]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {//---Проверяем был ли отправлен запрос "POST"
    $user = $_POST;
    $required = ['name', 'password', 'contact'];
    $errors = [];
    $error_massage = [
        'password' => 'Введите пароль',
        'name' => 'Введите имя',
        'contact' => 'Напишите как с вами связаться'
    ];
    //---Валидация паролля, имени, контактов---
    foreach ($required as $key) {
        if (empty($_POST[$key])) {
            $errors[$key] = $error_massage[$key];
        }
    }
    //---Валидация e-mail---
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Введите коректный e-mail';
    }

    //---Проверяем есть ли уже такаой e-mail в БД---
    $email = $_POST['email'];
    if (count(db_user_email($link, $email)) > 0) {
        $errors['email'] = 'Пользователь с этим e-mail уже зарегистрирован';
    }

    if (count($errors)) {
        $page_content = include_template('sign-up.php', [
            'user' => $user,
            'errors' => $errors,
            'categories' => $categories
        ]);
    } else {
        //---Добавление новой записи в таблицу users в MySQL---
        $user['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $sql = $db_add_user;
        $data = [
            $user['email'],
            $user['password'],
            $user['name'],
            $user['contact']
        ];
        $res = db_insert_user($link, $sql, $data);
        if ($res) {
            $layout_content = header("Location: pages/login.html");
        } else {
            $layout_content = error($is_auth, $user_name, $title, $categories);
        }
    }
} else {
    $page_content = include_template('sign-up.php', [
        'categories' => $categories,
    ]);
}

$layout_content = include_template('layout.php', [
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'title' => $title,
    'content' => $page_content,
    'categories' => $categories,
    'main_class' => 'class=" "'
]);
echo $layout_content;
