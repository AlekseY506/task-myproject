<?php 
//подключение к бд через PDO
include_once 'libs/db_connect.php';
include_once 'libs/functions.php';
//если нет сессии
if(!auth()){
    redirect("login-form.php");
}
$data = $_POST;
//проверяем массив данных на пустоту
if (empty($date) == ""){
    $_SESSION['error'] = "Пожалуйста заполните все поля";
    redirect("edit-form.php");
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
if (($attribute['title'] == '') and ($attribute['description'] == '')){
    $_SESSION['error'] = "Пожалуйста заполните все поля";
    redirect("edit-form.php");
}
//если фаил передан
if(!empty($_FILES['img']['tmp_name'])){
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
    if (!empty($_FILES['img']['error'])){
        $_SESSION['errors'] = "Произошла ошибка загрузки";
        redirect("edit-form.php?id={$_SESSION['id']}");
    }
    //Если тип загружаемого файла не разрешен
    if (!in_array($f_name['1'], $expension)){
        $_SESSION['errors'] = "Не допустимый формат файла. Выберите из следующих типов - .gif, .png, .jpg, .jpeg";
        redirect("edit-form.php?id={$_SESSION['id']}");
    }
    //Если размер файла превышает максимум
    if ($f_size > $maxSize){
        $_SESSION['errors'] = "Пожалуйста выберите фаил размером не более 2 мб.";
        redirect("edit-form.php?id={$_SESSION['id']}");
    }
    if (empty($_FILES['img']['error']) == true){
        move_uploaded_file($f_tmp, "assets/img/".$f_name['0'].$f_name['1']);
        //формируем запрос
        $sql = 'UPDATE articles SET title=:title, text=:text, images=:images WHERE id=:id';
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([':title' => $attribute['title'], ':text' => $attribute['description'], ':images' => $f_name['0'].$f_name['1'], ':id' => $_SESSION['id']]);
        //если ошибка
        if (!$result){
            $_SESSION['errors'] = "При изменении записи произошла ошибка, пожалуйста попробуйте позже";
            redirect("edit-form.php");
        }
        //успех переадресовываем на главную
        redirect("index.php");
    }
}
//формируем запрос
$sql = 'UPDATE articles SET title=:title, text=:text, images=:images WHERE id=:id';
$stmt = $pdo->prepare($sql);
$result = $stmt->execute([':title' => $attribute['title'], ':text' => $attribute['description'], ':images' => $_SESSION['image'], ':id' => $_SESSION['id']]);
unset($_SESSION['image']);
//если ошибка
if (!$result){
    $_SESSION['errors'] = "При изменении записи произошла ошибка, пожалуйста попробуйте позже";
    redirect("edit-form.php");
}
//успех переадресовываем на главную
redirect("index.php");

