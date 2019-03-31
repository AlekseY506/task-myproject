<?php

require_once 'libs/delete.php';
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
    header("Location: index.php");
    exit();
}
redirect("login-form.php");