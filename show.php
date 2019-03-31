<?php
    include_once 'libs/db_connect.php';
    include_once 'libs/functions.php';
    if(!auth()){
        redirect("login-form.php");
    }
    if (empty($_GET['id'])){
        redirect("index.php");
    }
    $id = htmlspecialchars(rtrim($_GET['id']));
    if (!is_numeric($id)){
        redirect("index.php");
    }
    $sql = 'SELECT id, title, text, images, date FROM articles WHERE user_id = :user_id AND id = :id LIMIT 1';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["user_id" => $_SESSION['user_id'], ":id" => $id]);
    $result = $stmt->fetch(PDO::FETCH_OBJ);
    if(!$result){
        redirect("index.php");
    }
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">

    <title>Show</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    
    <style>
      
    </style>
  </head>

  <body>
      <header>

      <div class="navbar navbar-dark bg-dark shadow-sm">
        <div class="container d-flex justify-content-between">
            <a href="index.php" class="navbar-brand d-flex align-items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
            <strong>Tasks-manager</strong>
          </a>
        </div>
      </div>
    </header>
    <div class="form-wrapper text-center">
      <img src="assets/img/<?=$result->images?>" alt="" width="600">
      <h2><?=$result->title?></h2>
      <p>
        <?=$result->text?>
      </p>
      <div class="d-flex justify-content-between align-items-center">
        <span class="date"><?=$result->date?></span>
        <span><a href="<?=$_SERVER['HTTP_REFERER']?>" class="navbar-brand d-flex align-items-center">Назад</a></span>
      </div>
    </div>
  </body>
</html>
