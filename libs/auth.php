<?php
function auth(){
    require_once 'db_connect.php';
    //если сессии нет 
    if (!isset($_SESSION['username'])){
        //если куки есть и он не пустой
        if ((isset($_COOKIE['login'])) || ($_COOKIE['login']) != ""){
            $hash = rtrim(htmlspecialchars($_COOKIE['login']));
            //формируем запрос
            $qwery = 'SELECT id, name, hash FROM users WHERE hash=:hash';
            $stmt = $pdo->prepare($qwery);
            $stmt->execute([':hash' => $hash]);
            $result = $stmt->fetch(PDO::FETCH_OBJ);
            //если совпадения есть
            if ($result){
                //создаем сессию пользователя
                $_SESSION['username'] = $result->name;
                $_SESSION['user_id'] = $result->id;
                return true;
            }
            return false;
        }
    }
    return true;
}