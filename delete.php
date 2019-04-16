<?php

require_once 'libs/db_connect.php';
include_once 'libs/functions.php';
//Если не гость
if(auth()){
    //Если id не передан
    if (empty($_GET['id'])){
    redirect("index.php");
    }
    //Экранируем спец символы удаляем пробелы
    $id = htmlspecialchars(rtrim($_GET['id']));
    //Если id не integer
    if (!is_numeric($id)){
        redirect("index.php");
    }
    //Формируем запросы к БД
    //Получаем имя файла задачи
    $sqlImg = "SELECT images FROM articles WHERE id=:id";
    $stmt = $pdo->prepare($sqlImg);
    $img = $stmt->execute([':id' => $id]);
    $img = $stmt->fetchColumn();
    //Если такого файла нет, просто удаляем задачу
    if (!file_exists("assets/img/" . $img)){
        $sql = "DELETE FROM articles WHERE id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
    }else{
        //Если не файл по умолчанию
        if ($img != "no_images.jpg"){
            //Удаляем фаил
            unlink("assets/img/" . $img);
        }
        //Удаляем задачу
        $sql = "DELETE FROM articles WHERE id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
    //Успех, на главную страницу
    redirect("index.php");
}
//На страницу авторизации
redirect("login-form.php");