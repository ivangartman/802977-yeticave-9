<?php

require_once 'include/init.php';

$categories = db_category_all($link);

if ($user_name) {
    $error_message = 'Вы уже вошли на сайт';
    $html = error($title, $categories, $error_message, $user_name, $pagecat, $search);
    exit();
} else {
    $page_content = include_template('login.php', [
        'categories' => $categories
    ]);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $login = $_POST;
        $errors = [];

        if (isset($login['password']) and empty(trim($login['password']))) {
            $errors['password'] = 'Введите пароль';
        } elseif (mb_strlen(trim($login['password'])) > 20) {
            $errors['password'] = 'Введите не более 20 символов';
        }
        if (isset($login['email']) and empty(trim($login['email']))) {
            $errors['email'] = 'Введите e-mail';
        } elseif (mb_strlen(trim($login['email'])) > 120) {
            $errors['email'] = 'Введите не более 120 символов';
        } else {
            //Проверяем есть ли такой E-mail в БД
            $email = mysqli_real_escape_string($link, trim($login['email']));
            $user = db_email($link, $email);
            if ($user) {
                if (password_verify(trim($login['password']), $user['password'])) {
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
