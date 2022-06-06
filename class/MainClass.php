<?php

use PDO;

/*
 * Основные методы для взаимодействия с базой данных
 * - Авторизация
 * - Регистрация
 * - ...
 */

class MainClass
{
    private $user;
    private $db;

    //Конструктор
    public function __construct($user = "empty")
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
                    Послать письмо подтверждения на почту.
                    */
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


    //Сокращение ссылки
    public function ShortLink($primerylink)
    {

        $user = $this->user;
        /*
         * Генерация кода для сокращенной ссылки.
         * Генерируем соль и создаем хэш строки.
         * Обрезаем созданный хэш до 6 символов.
         */
        //Генерируем соль
        $symbols = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; //Набор символов
        $salt = substr(str_shuffle($symbols), 0, 10); //Генерация строки

        //Определяем сегодняшний день и время для уникализации хэша
        $today = date("Y-m-d H:i:s");

        //Создаем хэш
        $hash = md5($salt, $primerylink, $user, $today);
        $string = substr($hash, 0, 6); //Обрезаем до 6 символов

        //Проверяем, есть ли такой код ссылки в базе данных:
        $rs = $this->db->query("SELECT * FROM link WHERE code_link = '$string'")->fetch();
        if (!empty($rs['link'])){
            //Создаем цикл для повторной генерации
            while (!empty($check['link']))
            {
                $symbols = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $salt = substr(str_shuffle($symbols), 0, 10);
                $today = date("Y-m-d H:i:s");
                $hash = md5($salt, $primerylink, $user, $today);
                $string = substr($hash, 0, 8);
            }
        }

    }
}