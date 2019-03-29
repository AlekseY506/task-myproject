<?php

$driver  = 'mysql';
$host    = 'localhost';
$dbname  = 'task-manager';
$dbuser  = 'root';
$dbpass  = '';
$charset = 'utf8';
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

try{
    $pdo = new PDO("$driver:host=$host;dbname=$dbname;charset=$charset", $dbuser, $dbpass, $options);
    session_start();
} catch (Exception $ex) {
die("Сервер перегружен попробуйте позже");
}