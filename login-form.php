<?php
    include_once 'libs/functions.php';
    if(auth()){
        redirect("index.php");
    }
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">

    <title>Login</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    
    <style>
      
    </style>
  </head>
  <body>
    <div class="form-wrapper text-center">
        <form class="form-signin" action="login.php" method="post">
            <img class="mb-4" src="assets/img/bootstrap-solid.svg" alt="" width="72" height="72">
            <h1 class="h3 mb-3 font-weight-normal">
                Авторизация
            </h1>
            <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?=$_SESSION['error'];unset($_SESSION['error']); ?>
            </div>
            <?php endif; ?>
            <?php if(isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?=$_SESSION['success'];unset($_SESSION['success']); ?>
            </div>
            <?php endif; ?>
            <label for="inputEmail" class="sr-only">
                Email
            </label>
            <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Email" required autofocus>
            <label for="inputPassword" class="sr-only">
                Пароль
            </label>
            <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Пароль" required>
            <div class="custom-control custom-checkbox mb-3">
                <input type="checkbox" name="remember_me" class="custom-control-input" id="customControlValidation1">
                <label class="custom-control-label" for="customControlValidation1">
                    Запомнить
                </label>
            </div>
            <button class="btn btn-lg btn-primary btn-block" type="submit">
                Войти
            </button>
            <a href="/register-form.php">
                Зарегистрироваться
            </a>
            <p class="mt-5 mb-3 text-muted">
                &copy; 2018 - <?=date("Y") ?>
            </p>
        </form>
    </div>
  </body>
</html>