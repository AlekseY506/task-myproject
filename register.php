<?php
//подключение к бд через PDO
include_once 'libs/db_connect.php';
include_once 'libs/functions.php';
//если пользователь авторизирован
if(auth()){
    header("Location: index.php");
    exit();
}
//массив POST с данными отправленные пользователем
$data = $_POST;
//проверяем массив данных на пустоту
if (empty($date) == ""){
    $_SESSION['error'] = "Пожалуйста заполните все поля";
    header("Location: login-form.php");
    exit();
}

//key дополнительная защита от подмены данных
$attribute = [
    'name'     => '',
    'email'    => '',
    'password' => '',
];
/*
 * смотрим если наш ключ совпадает с ключем массива пользователя
 * тогда записываем в atribute[key] значение переданное пользователем
 * перед тем удалив пробелы в начали и конце строки
 * и преобразуем спец символы в html сущности
 */
foreach ($attribute as $key => $value){
    $attribute[$key] = htmlspecialchars(rtrim($data[$key]));
}
//если attribute[key] пуст
if (($attribute['name'] == '') || ($attribute['email'] == '') || ($attribute['password'] == '')){
    $_SESSION['error'] = "Пожалуйста заполните все поля";
    header("Location: register-form.php");
    exit();
}
//если емаил введен верно тогда
if (filter_var($attribute['email'], FILTER_VALIDATE_EMAIL) !== false){
    //формируем запрос
    $sql = 'SELECT id FROM users WHERE email=:email';
    $statement = $pdo->prepare($sql);
    $statement->execute([':email' => $attribute['email']]);
    $user = $statement->fetchColumn();
    //если пользователь есть
    if ($user){
        $_SESSION['error'] = "Пользователь с таким email уже зарегистрирован";
        header("Location: register-form.php");
        exit();
    }
    
    //формируем запрос
    $sql = 'INSERT INTO users (name, email, password) VALUES (:name, :email, :password)';
    $statement = $pdo->prepare($sql);
    //для безопасности хешируем пароль и записываем в БД
    $attribute['password'] = password_hash($attribute['password'], PASSWORD_DEFAULT);
    $result = $statement->execute([':name' => $attribute['name'], ':email' => $attribute['email'], ':password' => $attribute['password']]);
    //если не получилось записать
    if (!$result){
        $_SESSION['error'] = "Ошибка, попробуйте позже";
        header("Location: register-form.php");
        exit();
    }
    
    //создаем сессию с сообщением об успехе
    $_SESSION['success'] = "Вы успешно зарегистрировались теперь можете войти на сайт";
    header("Location: login-form.php");
    exit();
} else {
    $_SESSION['error'] = "Email не соответствует формату";
    header("Location: register-form.php");
    exit();
}