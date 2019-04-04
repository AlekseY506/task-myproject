<?php

require_once 'libs/db_connect.php';
include_once 'libs/functions.php';
if(auth()){
    if (empty($_GET['id'])){
    redirect("index.php");
    }


    $id = htmlspecialchars(rtrim($_GET['id']));
    //delete($id);
    if (!is_numeric($id)){
        redirect("index.php");
    }
    $sql = "DELETE FROM articles WHERE id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    redirect("index.php");
}
redirect("login-form.php");