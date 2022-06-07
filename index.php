<?php
include_once './class/MainClass.php';
if (isset($_POST['login'])){
    $login = $_POST['login'];
    $pass = $_POST['pass'];
    $auth = new MainClass();
    $auth->AuthUser($login, $pass);
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Авторизация</title>
</head>
<body>
<form method="POST" action="./">
    <input type="text" name="login">
    <input type="password" name="pass">
    <input type="submit" value="Войти">
</form>
</body>
</html>
