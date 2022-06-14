<?php

/*
 * Определение главной страницы
 * Демонстрация главной страницы
 * Проверка подтверждения регистрации
 */

require_once 'db_connect.php';

//Главная страница
if ($_GET['show'] == 'main' || !isset($_GET['show'])){
    require './pages/mainlist.html';
}

//Подтверждение регистрации
if (isset($_GET['reg']))
{
    $hash = $_GET['hash'];
    $rs = $db->query("SELECT * FROM users WHERE hash = '$hash'")->fetch();
    if (!empty($rs['login']))
    {
        $one = 1;
        $db->query("UPDATE users SET status = '$one' WHERE hash = '$hash'");
        header("location: ../pages/authorizationlist.html");
    }
}
?>