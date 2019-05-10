<?php

require_once 'init.php';

$categories = db_category_all($link);

if (!$user_name) {
    $error_message = 'Для доступа к странице необходимо зарегистрироваться';
    $html = error($title, $categories, $error_message, $user_name);
    exit();
} else {
    $page_content = include_template('add.php', [
        'categories' => $categories
    ]);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {//Проверяем был ли отправлен запрос "POST"
        $lot = $_POST;
        $required = ['name', 'category_id', 'content'];
        $errors = [];
        $error_massage = [
            'name' => 'Введите наименование лота',
            'category_id' => 'Выберите категорию',
            'content' => 'Напишите описание лота'
        ];
        //Валидация имени, категории, описания
        foreach ($required as $key) {
            if (empty($_POST[$key])) {
                $errors[$key] = $error_massage[$key];
            }
        }
        //Валидация начальной ставки
        if ((int)($_POST['price']) === 0) {
            $errors['price'] = 'Введите целое число больше 0';
        }
        //Валидация шага ставки
        if ((int)($_POST['step_rate']) === 0) {
            $errors['step_rate'] = 'Введите целое число больше 0';
        }
        //Валидация даты ставки
        if (!is_date_valid($_POST['date_end'])) {
            $errors['date_end'] = 'Введите дату в формате ГГГГ-ММ-ДД';
        }
        //Валидация изображения
        if ($_FILES['lot-img'] ['name']) {
            $tmp_name = $_FILES['lot-img']['tmp_name'];
            $path = $_FILES['lot-img']['name'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $file_type = finfo_file($finfo, $tmp_name);
            if (($file_type === 'image/png') || ($file_type === 'image/jpeg') || ($file_type === 'image/jpg')) {
                $filename = uniqid() . '.' . substr($file_type, 6);
                $lot['picture_url'] = $filename;
                move_uploaded_file($_FILES['lot-img'] ['tmp_name'], 'uploads/' . $filename);
            } else {
                $errors['lot-img'] = 'Загрузите изображение в формате ipg, jpeg, png';
            }
        } else {
            $errors['lot-img'] = 'Загрузите изображение';
        }

        if (count($errors)) {
            $page_content = include_template('add.php', [
                'lot' => $lot,
                'errors' => $errors,
                'categories' => $categories
            ]);
        } else {
            //Добавление новой записи в таблицу lots в MySQL
            $lot['user_id'] = $_SESSION['user']['id'];
            $sql = $db_add_lot;
            $data = [
                $lot['user_id'],
                $lot['category_id'],
                $lot['name'],
                $lot['content'],
                'uploads/' . $lot['picture_url'],
                $lot['price'],
                $lot['date_end'],
                $lot['step_rate']
            ];
            $res = db_insert($link, $sql, $data);
            if ($res) {
                $lot_id = mysqli_insert_id($link);
                header("Location: lot.php?page=" . $lot_id);
            } else {
                $error_message = 'Новый лот не добавлен';
                $html = error($title, $categories, $error_message, $user_name);
            }
        }
    } else {
        $page_content = include_template('add.php', [
            'categories' => $categories
        ]);
    }
}

$html = include_template('layout.php', [
    'user_name' => $user_name,
    'title' => $title,
    'content' => $page_content,
    'categories' => $categories,
    'main_class' => 'class=" "'
]);
echo $html;
