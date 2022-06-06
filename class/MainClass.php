<?php

use PDO;
/*
 * Основные методы для взаимодействия с базой данных
 * - Авторизация
 * - Регистрация
 * - ...
 * Важно! Отсутствуют проверки в запросах и защита бд!
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
            if (!empty($auth['login'])) {
                if ($upass == $auth['password']) {
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
            if (empty($reg['login'])) {
                if (empty($reg['email'])) {
                    if ($pass == $rpass) {
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
            $status = 0; //Начальный статус (ссылка работает)
            $user = $this->user;
            /*
             * Генерация кода для сокращенной ссылки.
             * Генерируем соль и создаем хэш строки.
             */
            //Генерируем соль
            $symbols = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; //Набор символов
            $salt = substr(str_shuffle($symbols), 0, 10); //Генерация строки

            //Определяем сегодняшний день и время для уникализации хэша
            $today = date("Y-m-d H:i:s");

            //Создаем хэш
            $hash = md5($salt, $primerylink, $user, $today);
            $result = substr(str_shuffle($hash), 0, 6); //Полученный код
            /*
             * необходимо использовать библиотеку для генерации уникального идентификатора!
             * Здесь не используется, так как это демонстративная версия
             */

            //Добавляем запись в бд
            $inbase = $this->db->query("INSERT INTO link VALUES (NULL, '$result', '$primerylink', '$user', '$status')");
            return $result; //Вернем созданный код
        }


        //Изменение статуса ссылки
        public function UpdateStatus($status, $id)
        {
            //Проверяем текущий статус
            $rs = $this->db->query("SELECT * FROM link WHERE id='$id'")->fetch();

            //Если текущий статус не равен устанавливаемому, то меняем
            if ($rs['status'] != $status)
            {
                $this->db->query("UPDATE link SET status = '$status' WHERE id = '$id'");
            }
        }


        //Удаление ссылки
        public function DeleteLink($id)
        {
            //Проверяем, существует ли такая ссылка
        }
}