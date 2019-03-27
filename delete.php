<?php

require_once 'libs/delete.php';
include_once 'libs/functions.php';
if(auth() != false){
    if (!isset($_GET['id'])){
    header("Location: index.php");
    exit();
    }


    $id = htmlspecialchars(rtrim($_GET['id']));
    //delete($id);
    if (!is_numeric($id)){
        header("Location: index.php");
        exit();
    }
    $sql = "DELETE FROM articles WHERE id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    header("Location: index.php");
    exit();
}
header("Location: login-form.php");
exit();