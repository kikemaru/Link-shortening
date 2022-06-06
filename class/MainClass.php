<?php

use PDO;

class MainClass
{
    private $user;
    private $db;
    private $idlink;

    public function __construct($user, $id = "empty")
    {
        $this->user = $user;
        $this->idlink = $id;
        $this->db = new PDO('mysql:host=localhost;dbname=short', 'root', '');
    }

}