<?php

include_once 'libs/db_connect.php';
include_once 'libs/functions.php';
if(!auth()){
    redirect("login-form.php");
}
$data = $_POST;

$attribute = [
    'title'       => '',
    'description' => '',
];

foreach ($attribute as $key => $value){
    $attribute[$key] = htmlspecialchars(rtrim($data[$key]));
}

if ($attribute['title'] == ''){
    $_SESSION['error'] = "Отсутсквует описание заголовка";
    redirect("create-form.php");
}

if ($attribute['description'] == ''){
    $_SESSION['error'] = "Отсутствует текст статьи";
    redirect("create-form.php");
}
//фаил фото
if(!empty($_FILES['img']['tmp_name'])) {
    $expension = ['.jpeg', '.jpg', '.png', '.gif'];     //Разрешенные типы файлов
    $maxSize = 2 * 1024 * 1024;                         //Максимально допустимый размер загружаемого файла в мегабайтах
    $f_name = $_FILES['img']['name'];                   //Имя загружаемого файла
    $f_size = $_FILES['img']['size'];                   //Pазмер загружаемого файла
    $f_tmp = $_FILES['img']['tmp_name'];
    $f_type = $_FILES['img']['type'];
    $f_name = explode(".", $f_name);           //разбиваем строку на имя[0] и тип[1]
    $f_name['1'] = "." . $f_name['1'];                  //в начало нашего типа ставим точку
    $f_name['0'] = md5($f_name['0']) . "_" . date("Y-m-d_h-i-s");    //для уникальность хешируем имя и добовляем текещее дату и время
    //проверяем наличие ошибок
    if (!empty($_FILES['img']['error'])) {
        $_SESSION['errors'] = "Произошла ошибка загрузки";
        redirect("edit-form.php?id={$_SESSION['id']}");
    }
    //Если тип загружаемого файла не разрешен
    if (!in_array($f_name['1'], $expension)) {
        $_SESSION['errors'] = "Не допустимый формат файла. Выберите из следующих типов - .gif, .png, .jpg, .jpeg";
        redirect("edit-form.php?id={$_SESSION['id']}");
    }
    //Если размер файла превышает максимум
    if ($f_size > $maxSize) {
        $_SESSION['errors'] = "Пожалуйста выберите фаил размером не более 2 мб.";
        redirect("edit-form.php?id={$_SESSION['id']}");
    }
    if (empty($_FILES['img']['error']) == true) {
        move_uploaded_file($f_tmp, "assets/img/" . $f_name['0'] . $f_name['1']);
        //формируем запрос
        $sql = 'INSERT INTO articles (user_id, title, text, images) VALUES (:user_id, :title, :text, :images)';
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([':user_id' => $_SESSION['user_id'], ':title' => $attribute['title'], ':text' => $attribute['description'], ':images' => $f_name[0] . $f_name[1]]);
        //если ошибка
        if (!$result) {
            $_SESSION['errors'] = "При изменении записи произошла ошибка, пожалуйста попробуйте позже";
            redirect("edit-form.php");
        }
        //успех переадресовываем на главную
        redirect("index.php");
    }
}

    $sql = 'INSERT INTO articles (user_id, title, text) VALUES (:user_id, :title, :text)';
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([':user_id' => $_SESSION['user_id'], ':title' => $attribute['title'], ':text' => $attribute['description']]);
    if (!$result){
        $_SESSION['errors'] = "При добавлении записи произошла ошибка, пожалуйста попробуйте позже";
        redirect("create-form.php");
    } else {
        redirect("index.php");
    }
