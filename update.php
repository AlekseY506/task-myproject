<?php 
//подключение к бд через PDO
include_once 'db_connect.php';
//если нет сессии
if(!isset($_SESSION['username'])){
    header("Location: index.php");
    exit();
}
$data = $_POST;
//проверяем массив данных на пустоту
if (empty($date) == ""){
    $_SESSION['error'] = "Пожалуйста заполните все поля";
    header("Location: login-form.php");
    exit();
}
//key дополнительная защита от подмены данных
$attribute = [
    'title'  => '',
    'description'   => '',
];
/*
 * смотрим если наш ключ совпадает с ключем массива пользователя
 * тогда записываем в attribute[key] значение переданное пользователем
 * перед тем удалив пробелы в начали и конце строки
 * и преобразуем спец символы в html сущности
 */
foreach ($attribute as $key => $value){
    $attribute[$key] = htmlspecialchars(rtrim($data[$key]));
}
//если attribute[key] пуст
if (($attribute['title'] == '') || ($attribute['description'] == '')){
    $_SESSION['error'] = "Пожалуйста заполните все поля";
    header("Location: edit-form.php");
    exit();
}
//если фаил передан
if(!empty($_FILES['img']['tmp_name'])){
    
    $f_name = $_FILES['img']['name'];
    $f_size = $_FILES['img']['size'];
    $f_tmp = $_FILES['img']['tmp_name'];
    $f_type = $_FILES['img']['type'];
    //проверяем наличие ошибок
    if (!empty($_FILES['img']['error'])){
        $_SESSION['errors'] = "Произошла ошибка загрузки";
        header("Location: edit-form.php");
        exit();
    }
    //допустимый размер файла
    if ($f_size > 8 * 1024 * 1024){
        $_SESSION['errors'] = "Пожалуйста выберите фаил размером не более 8 мб.";
        header("Location: edit-form.php");
        exit();
    }
    //$expensions = ["jpeg","jpg","png"]; не понятно как это можно использовать для проверки
    if (empty($_FILES['img']['error']) == true){
        move_uploaded_file($f_tmp, "assets/img/".$f_name);
        //формируем запрос
        $sql = 'UPDATE articles SET title=:title, text=:text, images=:images WHERE id=:id';
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([':title' => $attribute['title'], ':text' => $attribute['description'], ':images' => $f_name, ':id' => $_SESSION['id']]);
        //если ошибка
        if (!$result){
            $_SESSION['errors'] = "При изменении записи произошла ошибка, пожалуйста попробуйте позже";
            header("Location: edit-form.php");
            exit();
        }
        //успех переадресовываем на главную
        header("Location: index.php");
        exit();
    }
    
}
//формируем запрос
$sql = 'UPDATE articles SET title=:title, text=:text, images=:images WHERE id=:id';
$stmt = $pdo->prepare($sql);
$result = $stmt->execute([':title' => $attribute['title'], ':text' => $attribute['description'], ':images' => $_SESSION['image'], ':id' => $_SESSION['id']]);
//если ошибка
if (!$result){
    $_SESSION['errors'] = "При изменении записи произошла ошибка, пожалуйста попробуйте позже";
    header("Location: edit-form.php");
    exit();
}
//успех переадресовываем на главную
header("Location: index.php");
exit();
