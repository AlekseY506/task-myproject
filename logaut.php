<?php
include_once 'libs/functions.php';
session_start();
unset($_SESSION['auth']);
unset($_SESSION['username']);
unset($_SESSION['user_id']);
setcookie("login","", time());
redirect("login-form.php");

