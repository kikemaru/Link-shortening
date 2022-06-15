<?php

use PDO; //Пространство имен PDO
/*
 * Основные методы для взаимодействия с базой данных
 * - Авторизация
 * - Регистрация
 * - ...
 * Важно! Отсутствуют проверки в запросах и защита бд!
 * Также, возможно упрощение sql запросов
 */

    class MainClass
    {
        private $user;
        private $db;

        //Конструктор
        public function __construct($user = "empty")
        {
            $this->user = $user; //id пользователя
            /*
             * Используется PDO для работы с бд
             * Для настройки подключения необходимо изменить параметры в свойстве db
             *
             */
            $this->db = new PDO('mysql:host=localhost;dbname=short', 'root', ''); //Подключение к бд
        }


        //Авторизация пользователя
        public function AuthUser($login, $password)
        {
            $ulogin = $login;
            $upass = md5($password);

            $auth = $this->db->query("SELECT * FROM users WHERE login = '$ulogin'")->fetch();
            if (!empty($auth['login'])) {
                if ($upass == $auth['password']) {
                    return "success";
                } else {
                    return "password";
                    //Неверный пароль
                }
            } else {
                return "user";
                //Несуществующий пользователь
            }
        }




        //Регистрация пользователя
        public function RegUser($login, $email, $pass, $rpass)
        {
            $password = md5($pass);
            $null = 0;

            $today = date("H:i:s");
            $hash = md5("$login and $today");

            $reg = $this->db->query("SELECT * FROM users WHERE login = '$login'")->fetch();
            if (empty($reg['login'])) {
                if (empty($reg['email'])) {
                    if ($pass == $rpass) {
                        echo $hash;
                        //Регистрируем пользователя.
                       $this->db->query("INSERT INTO users VALUES (NULL, '$login', '$password', '$email', '$null', '$hash')");
                        //Послать письмо подтверждения на почту.
                        mail($email, "Подтверждение регистрации", "Перейдите по ссылке, чтобы подтвердить регистрацию \n http://localhost/short/?reg=$hash");

                        //Если был запрос на создание ссылки
                        $rsl = $this->db->query("SELECT * FROM users WHERE login = '$login'")->fetch();
                        $iduser = $rsl['id'];
                        $this->ShortLink($_SESSION['new_link'], $iduser);
                        return "success";
                    } else {
                        return "errorpass";
                    }
                } else {
                    return "errormail";
                }
            } else {
                return "errorlogin";
            }
        }




        //Сокращение ссылки
        public function ShortLink($primerylink, $uid = '')
        {
            $status = 0; //Начальный статус (ссылка работает)
            if (empty($uid)) {
                $user = $this->user;
            } else {
                $user = $uid;
            }
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
            $hash = md5("$salt - $primerylink - $user - $today");
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
            $rs = $this->db->query("SELECT * FROM link WHERE id='$id'")->fetch();
            if (!empty($rs['code_link']))
            {
                //Реализуем транзакцию, чтобы в комплекте удалять все логи данной ссылки
                try {
                    $this->db->beginTransaction();
                    $this->db->exec("DELETE FROM link WHERE id='$id'"); //Удаляем ссылку
                    $this->db->exec("DELETE FROM log WHERE id_link = '$id'"); //Удаляем логи
                } catch (PDOException $error){
                    $this->db->rollBack();
                    exit();
                }
                $this->db->commit();
            }
        }




        //Очистка логов определенной ссылки
        public function CleanLog($id)
        {

            //Проверяем существует ли такая ссылка
            $rs = $this->db->query("SELECT * FROM link WHERE id='$id'")->fetch();

            if (!empty($rs['code_link']))
            {

                //Очищаем весь лог
                $clean = $this->db->query("DELETE FROM log WHERE id_link = '$id'");
            }
        }




        //Выгрузка файла с логом ссылки
        public function DownloadLog($id)
        {

            //Вывод данных
            $rs = $this->db->query("SELECT * FROM log WHERE id_link='$id'")->fetch();

            $date = $rs['date_time']; //Дата и время лога
            $client = $rs['client']; //Браузер (устройство)
            $ref = $rs['referer']; //Откуда переход
            $ip = $rs['ip']; //ip адрес


                //создаем виртуальный файл
                header('Content-disposition: attachment; filename=log.txt');
                header('Content-type: text/plain');
                // делаем запись в файл
                echo "[ ";
                echo " Дата: $date |";
                echo " Client: $client |";
                echo " Откуда: $ref |";
                echo " Ip: $ip ] \r\n";

                /*
                 * Желательно переделать и организовать генерацию pdf файла
                 * для pdf использовать библиотеку dompdf
                 */


            }


            //Получение статуса
        public function GetStatus($status)
        {
            switch ($status) {
                case 0:
                    return '<font color="green">Работает</font>';
                    break;
                case 1:
                    return '<font color="orange">Отключена</font>';
                    break;
                case 2:
                    return '<font color="red">Заблокирована</font>';
                    break;
            }
        }




        //Выход
        function UserQuit()
        {
            //Уничтожение сессии
            unset($_SESSION['name']);
            header("location: ../");
        }



}