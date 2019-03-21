<?php 
session_start();
if (!isset($_SESSION['username'])){
    header("Location: login-form.php");
    exit();
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">

    <title>Create Task</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    
    <style>
      
    </style>
  </head>

  <body>
    <div class="form-wrapper text-center">
        <form class="form-signin" action="post.php" method="post" enctype="multipart/form-data">
        <img class="mb-4" src="assets/img/bootstrap-solid.svg" alt="" width="72" height="72">
        <h1 class="h3 mb-3 font-weight-normal">Добавить запись</h1>
        <label for="inputEmail" class="sr-only">Название</label>
        <input type="text" name="title" id="inputEmail" class="form-control" placeholder="Название" required>
        <label for="inputEmail" class="sr-only">Описание</label>
        <textarea name="description" class="form-control" cols="30" rows="10" placeholder="Описание"></textarea>
        <input type="file" name="img">
        <?php if(isset($_SESSION['errors'])): ?>
            <div class="alert alert-danger">
                <?=$_SESSION['errors'];unset($_SESSION['errors']); ?>
            </div>
        <?php endif; ?>
        <button class="btn btn-lg btn-success btn-block" type="submit">Добавить</button>
        <a href="index.php" class="btn btn-lg btn-primary btn-block" type="submit">на главную</a>
        <p class="mt-5 mb-3 text-muted">&copy; 2018 - <?=date("Y") ?></p>
      </form>
    </div>
  </body>
</html>