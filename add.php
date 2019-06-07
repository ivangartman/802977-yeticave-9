<?php

require_once 'include/init.php';

$categories = db_category_all($link);

if (! $user_name) {
    $error_message = 'Для доступа к странице необходимо войти в личный кабинет';
    $html = error($title, $categories, $error_message, $user_name, $pagecat, $search);
    exit();
} else {
    $page_content = include_template('add.php', [
        'categories' => $categories
    ]);
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $lot = $_POST;
        $errors = [];
        //Валидация имени
        if (empty($_POST['name'])) {
            $errors['name'] = 'Введите наименование лота';
        } elseif (mb_strlen($_POST['name']) > 120) {
            $errors['name'] = 'Слишком длинное имя';
        }
        //Валидация категории
        if (empty($_POST['category_id'])) {
            $errors['category_id'] = 'Выберите категорию';
        } elseif (count(db_category_id($link, $_POST['category_id'])) === 0) {
            $errors['category_id'] = 'Такой категории не существует';
        }
        //Валидация описания лота
        if (empty($_POST['content'])) {
            $errors['content'] = 'Напишите описание лота';
        } elseif (mb_strlen($_POST['name']) > 500) {
            $errors['content'] = 'Слишком длинное описание лота';
        }
        //Валидация начальной ставки
        if (empty($_POST['price'])) {
            $errors['price'] = 'Введите начальную цену';
        } elseif (! filter_var($_POST['price'], FILTER_VALIDATE_INT)){
            $errors['price'] = 'Введите целое число больше 0';
        } elseif (mb_strlen($_POST['price']) > 11) {
            $errors['price'] = 'Введите не более 11 цифр';
        }
        //Валидация шага ставки
        if (empty($_POST['step_rate'])) {
            $errors['step_rate'] = 'Введите шаг ставки';
        } elseif (! filter_var($_POST['step_rate'], FILTER_VALIDATE_INT)){
            $errors['step_rate'] = 'Введите целое число больше 0';
        } elseif (mb_strlen($_POST['step_rate']) > 11) {
            $errors['step_rate'] = 'Введите не более 11 цифр';
        }
        //Валидация даты ставки
        $tomorrow_Date = new DateTime('tomorrow');
        if (empty($_POST['date_end'])) {
            $errors['date_end'] = "Введите дату завершения торгов";
        } else if (!is_date_valid($_POST['date_end'])) {
            $errors['date_end'] = "Введите дату в формате ГГГГ-ММ-ДД";
        } else if ($_POST['date_end'] < $tomorrow_Date->format('Y-m-d')) {
            $errors['date_end'] = 'Введите дату больше текущей, хотя бы на один день';
        };

        //Валидация изображения
        if (isset($_FILES['lot-img'] ['name']) and $_FILES['lot-img'] ['name']) {
            $tmp_name = $_FILES['lot-img']['tmp_name'];
            $path = $_FILES['lot-img']['name'];
            $file_type = $tmp_name != '' ? mime_content_type($tmp_name) : '';
            if (($file_type != 'image/png') and ($file_type != 'image/jpeg') and ($file_type != 'image/jpg')) {
                $errors['lot-img'] = 'Загрузите изображение в формате ipg, jpeg, png';
            }
        } else {
            $errors['lot-img'] = 'Загрузите изображение';
        }
        if (count($errors)) {
            $page_content = include_template('add.php', [
                'lot'        => $lot,
                'errors'     => $errors,
                'categories' => $categories
            ]);
        } else {
            //Загрузка изображения на сайт
            $filename = uniqid() . '.' . substr($file_type, 6);
            $lot['picture_url'] = $filename;
            move_uploaded_file($_FILES['lot-img'] ['tmp_name'],'uploads/' . $filename);
            //Добавление новой записи в таблицу lots в MySQL
            $lot['user_id'] = $_SESSION['user']['id'];
            $sql = $db_add_lot;
            $data = [
                $lot['user_id'],
                $lot['category_id'],
                $lot['name'],
                $lot['content'],
                'uploads/'.$lot['picture_url'],
                $lot['price'],
                $lot['date_end'],
                $lot['step_rate']
            ];
            $res = db_insert($link, $sql, $data);
            if ($res) {
                $lot_id = mysqli_insert_id($link);
                header("Location: lot.php?page=".$lot_id);
            } else {
                $error_message = 'Новый лот не добавлен';
                $html = error($title, $categories, $error_message, $user_name, $pagecat, $search);
            }
        }
    } else {
        $page_content = include_template('add.php', [
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
