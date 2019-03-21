<?php

include_once 'db_connect.php';
if (!isset($_SESSION['username'])){
    header("Location: login-form.php");
    exit();
}
if (!isset($_GET['id'])){
    header("Location: index.php");
    exit();
}
$id = htmlspecialchars(rtrim($_GET['id']));
if ($id == ""){
    header("Location: index.php");
    exit();
}
if (!is_numeric($id)){
    header("Location: index.php");
    exit();
}
$sql = "DELETE FROM articles WHERE id=:id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);
header("Location: index.php");
exit();

