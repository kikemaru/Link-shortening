<?php

use PDO;

class MainClass
{
    private $user;
    private $db;

    public function __construct($user = "empty", $id = "empty")
    {
        $this->user = $user;
        $this->db = new PDO('mysql:host=localhost;dbname=short', 'root', '');
    }


    //Авторизация пользователя
    public function AuthUser($login, $password)
    {
        $ulogin = $login;
        $upass = md5($password);

        $auth = $this->db->query("SELECT * FROM users WHERE login = '$ulogin'")->fetch();
        if (!empty($auth['login'])){
            if ($upass == $auth['password']){
                echo "OK";
            } else {
                echo "Error pass";
                //Неверный пароль
            }
        } else {
            echo "Error user";
            //Несуществующий пользователь
        }
    }


    //Регистрация пользователя
    public function RegUser($login, $email, $pass, $rpass)
    {
        $password = md5($pass);

        $reg = $this->db->query("SELECT * FROM users WHERE login = '$login'")->fetch();
        if (empty($reg['login'])){
            if (empty($reg['email'])){
                if ($pass == $rpass){
                    /*Регистрируем пользователя.
                    Послать письмо подтверждения на почту*/
                } else {
                    //Не совпадают пароли!
                }
            } else {
                //Такая эл.почта уже используется!
            }
        } else {
            //Такой логин уже используется!
        }
    }
}