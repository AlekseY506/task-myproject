<?php
//подключение к бд через PDO
include_once 'libs/db_connect.php';
//если пользователь не авторизирован
if (!isset($_SESSION['username'])){
    header("Location: login-form.php");
    exit();
}
//если не передан id
if (!isset($_GET['id'])){
    header("Location: index.php");
    exit();
}

//удалив пробелы в начали и конце строки и преобразуем спец символы в html сущности
$id = htmlspecialchars(rtrim($_GET['id']));
//проверяем на пустоту
if ($id == ""){
    header("Location: index.php");
    exit();
}
//если не цифра
if (!is_numeric($id)){
    header("Location: index.php");
    exit();
}
//формируем запрос
$sql = 'SELECT * FROM articles WHERE id=:id and user_id=:user_id';
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id, ':user_id' => $_SESSION['user_id']]);
//получаем результат ввиде объекта
$articles = $stmt->fetch(PDO::FETCH_OBJ);
//для дальнейшей возможности манипулирования данными при редактировании
//создаем сессии id и imaje
$_SESSION['image'] = $articles->images;
$_SESSION['id'] = $id;

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">

    <title>Edit Task</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    
    <style>
      
    </style>
  </head>

  <body>
    <div class="form-wrapper text-center">
        <form class="form-signin" action="update.php" method="post" enctype="multipart/form-data">
        <img class="mb-4" src="assets/img/bootstrap-solid.svg" alt="" width="72" height="72">
        <h1 class="h3 mb-3 font-weight-normal">Добавить запись</h1>
        <?php if(isset($_SESSION['errors'])): ?>
            <div class="alert alert-danger">
                <?=$_SESSION['errors'];unset($_SESSION['errors']); ?>
            </div>
        <?php endif; ?>
        <label for="inputEmail" class="sr-only">Название</label>
        <input type="text" name="title" id="inputEmail" class="form-control" placeholder="Название" required value="<?=$articles->title?>">
        <label for="inputEmail" class="sr-only">Описание</label>
        <textarea name="description" class="form-control" cols="30" rows="10" placeholder="Описание" ><?=$articles->text?></textarea>
        <input type="file" name="img">
        <img src="assets/img/<?=$articles->images?>" alt="" width="300" class="mb-3">
        <button class="btn btn-lg btn-success btn-block" type="submit">Редактировать</button>
        <a href="index.php" class="btn btn-lg btn-primary btn-block" type="submit">на главную</a>
        <p class="mt-5 mb-3 text-muted">&copy; 2018 - <?=date("Y") ?></p>
      </form>
    </div>
  </body>
</html>
