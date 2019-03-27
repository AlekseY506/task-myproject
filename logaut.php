<?php
session_start();
unset($_SESSION['auth']);
unset($_SESSION['username']);
unset($_SESSION['user_id']);
setcookie("login","", time());
header("Location: login-form.php");
exit();

