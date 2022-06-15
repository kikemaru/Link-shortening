<?php

session_start();
require_once '../class/MainClass.php';
$main = new MainClass();
/*
 * Содержатся функции не имеющие отношения к взаимодействию "User - DataBase"
 * А также описано взаимодействие между методами и формами
 * Работа с сеансами
 */

//Первое использование
if (isset($_POST['link'])){
    $_SESSION['new_link'] = $_POST['link'];
    header("location: ../pages/reglist.html");
    //Регистрация
} elseif (isset($_POST['rpassword'])){
    $reg = $main->RegUser($_POST['login'], $_POST['email'], $_POST['password'], $_POST['rpassword']);
    if ($reg == 'success'){
        header("location: ../");
    } elseif ($reg == 'errorpass'){
        header("location: ../pages/reglist.html?pass");
    } elseif ($reg == 'errormail'){
        header("location: ../pages/reglist.html?mail");
    } elseif ($reg == 'errorlogin'){
        header("location: ../pages/reglist.html?login");
    }
    //Авторизация
} elseif (isset($_POST['login'])){
    $auth = $main->AuthUser($_POST['login'], $_POST['password']);
    if ($auth == 'success'){
        $_SESSION['name'] = $_POST['login'];
        header("location: ../private/");
    } elseif ($auth == 'password'){
        header("location: ../pages/authorizationlist.html?pass");
    } elseif ($auth == 'user'){
        header("location: ../pages/authorizationlist.html?user");
    }
}



