<?php

include_once 'db_connect.php';
if(!isset($_SESSION['username'])){
    header("Location: login-form.php");
    exit();
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
    header("Location: create-form.php");
    exit();
}

if ($attribute['description'] == ''){
    $_SESSION['error'] = "Отсутствует текст статьи";
    header("Location: create-form.php");
    exit();
}
//фаил фото
if(!empty($_FILES['img']['tmp_name'])){
    $f_name = $_FILES['img']['name'];
    $f_size = $_FILES['img']['size'];
    $f_tmp = $_FILES['img']['tmp_name'];
    $f_type = $_FILES['img']['type'];
    //проверяем наличие ошибок
    if (!empty($_FILES['img']['error'])){
        $_SESSION['errors'] = "Произошла ошибка загрузки";
        header("Location: create-form.php");
        exit();
    }
    //допустимый размер файла
    if ($f_size > 8 * 1024 * 1024){
        $_SESSION['errors'] = "Пожалуйста выберите фаил размером не более 8 мб.";
        header("Location: create-form.php");
        exit();
    }
    $expensions = ["jpeg","jpg","png"];
    
    if (empty($_FILES['img']['error']) == trye){
        move_uploaded_file($f_tmp,"assets/img/".$f_name);
        $sql = 'INSERT INTO articles (user_id, title, text, images) VALUES (:user_id, :title, :text, :images)';
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([':user_id' => $_SESSION['user_id'], ':title' => $attribute['title'], ':text' => $attribute['description'], ':images' => $f_name]);
            if (!$result){
                $_SESSION['errors'] = "При добавлении записи произошла ошибка, пожалуйста попробуйте позже";
                header("Location: create-form.php");
                exit();
            } else {
                header("Location: index.php");
                exit();
            }
    }
}else{
    $sql = 'INSERT INTO articles (user_id, title, text, images) VALUES (:user_id, :title, :text, :images)';
    $stmt = $pdo->prepare($sql);
    $f_name = "no_images.jpg";
    $result = $stmt->execute([':user_id' => $_SESSION['user_id'], ':title' => $attribute['title'], ':text' => $attribute['description'], ':images' => $f_name]);
    if (!$result){
        $_SESSION['errors'] = "При добавлении записи произошла ошибка, пожалуйста попробуйте позже";
        header("Location: create-form.php");
        exit();
    } else {
        header("Location: index.php");
        exit();
    }
}