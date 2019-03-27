<?php
//подключение к бд через PDO
include_once 'libs/db_connect.php';
include_once 'libs/functions.php';
//если нет сессии
if(auth() != false){
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
    'remember_me'    => 'off',
    'name'     => '',
    'email'    => '',
    'password' => '',
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
if (($attribute['email'] == '') || ($attribute['password'] == '')){
    $_SESSION['error'] = "Вы пропустили поле для заполнения";
    header("Location: login-form.php");
    exit();
}

//если емаил введен верно тогда
if (filter_var($attribute['email'], FILTER_VALIDATE_EMAIL) !== false){
    //запрос к Базе Данных
    $sql = 'SELECT id, name, password, email FROM users WHERE email=:email';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":email" => $attribute['email']]);
    //получаем результат ввиде объекта
    $result = $stmt->fetch(PDO::FETCH_OBJ);
    if ($result){
        if ( password_verify($attribute['password'], $result->password)){
            $_SESSION['username'] = $result->name;
            $_SESSION['user_id'] = $result->id;
            $_SESSION['auth'] = true;
            //если нужно запомнить пользователя
            if (isset($attribute['remember_me']) && $attribute['remember_me'] == 'on'){
                //соль
                $salt = time();
                //хешируем соль + емаил , записываем в cokies 
                //обновляем хеш и соль в бд
                $hash = htmlspecialchars(password_hash($salt.$attribute['email'], PASSWORD_DEFAULT));
                setcookie("login", $hash, time()+ 3600 * 24 * 7);
                //формируем запрос
                $sql = 'UPDATE users SET hash=:hash, salt=:salt WHERE email=:email';
                $stmt = $pdo->prepare($sql);
                $result = $stmt->execute([":hash" => $hash, ":salt" => $salt, ":email" => $attribute['email']]);
                //переадресовываем на главную
                header("Location: index.php");
                exit();
            }
            //переадресовываем на главную
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['error'] = "Пользователь с таким Email или паролём не зарегистрирован";
            header("Location: login-form.php");
            exit();
        }
    }else{
        $_SESSION['error'] = "Пользователь с таким Email или паролём не зарегистрирован";
        header("Location: login-form.php");
        exit();
    }
}else{
    $_SESSION['error'] = "Email не соответствует формату";
    header("Location: login-form.php");
    exit();
}