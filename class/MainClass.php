<?php

use PDO;

class MainClass
{
    private $user;
    private $db;
    private $idlink;

    public function __construct($user = "empty", $id = "empty")
    {
        $this->user = $user;
        $this->idlink = $id;
        $this->db = new PDO('mysql:host=localhost;dbname=short', 'root', '');
    }

    public function AuthUser($login, $password){
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
}