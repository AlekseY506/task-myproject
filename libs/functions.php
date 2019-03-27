<?php

function redirect($http = false){
    $redirect = $http;
    if ($redirect){
        header("Location: $redirect");
    }
}

function auth(){
    require_once 'db_connect.php';
    //если сессии нет 
    if (!isset($_SESSION['auth'])){
        //если куки есть и он не пустой
        if ((isset($_COOKIE['login'])) || ($_COOKIE['login']) != ""){
            $hash = rtrim(htmlspecialchars($_COOKIE['login']));
            //формируем запрос
            global $pdo;
            $qwery = 'SELECT id, name, email, hash FROM users WHERE hash=:hash';
            $stmt = $pdo->prepare($qwery);
            $stmt->execute([':hash' => $hash]);
            $result = $stmt->fetch(PDO::FETCH_OBJ);
            //если совпадения есть
            if ($result){
                //создаем сессию пользователя
                $_SESSION['auth'] = true;
                $_SESSION['username'] = $result->name;
                $_SESSION['user_id'] = $result->id;
                //соль
                $salt = time();
                //хешируем соль + емаил , записываем в cokies 
                //обновляем хеш и соль в бд
                $hash = htmlspecialchars(password_hash($salt.$result->email, PASSWORD_DEFAULT));
                setcookie("login", $hash, time()+ 3600 * 24 * 7);
                //формируем запрос
                $sql = 'UPDATE users SET hash=:hash, salt=:salt WHERE email=:email';
                $stmt = $pdo->prepare($sql);
                $result = $stmt->execute([":hash" => $hash, ":salt" => $salt, ":email" => $result->email]);
                //переадресовываем на главную
                header("Location: index.php");
                exit();
            }
            return true;
        }
        return false;
    }
    return true;
}

//function delete($id){
//    if (!is_numeric($id)){
//    header("Location: index.php");
//    exit();
//    }
//    $sql = "DELETE FROM articles WHERE id=:id";
//    $stmt = $pdo->prepare($sql);
//    $stmt->execute([':id' => $id]);
//    return true;
//}