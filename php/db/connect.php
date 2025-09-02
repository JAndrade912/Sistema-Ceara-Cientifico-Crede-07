<?php

class Database {
    private $host = "localhost";
    private $dbname = "SACC";
    private $username = "root";
    private $password = "cr701201";
    public $conn;

    public function connect()
    {
        try {
            $this -> conn = new PDO("mysql:host={$this -> host};dbname={$this -> dbname};charset=utf8", $this -> username, $this -> password);
            $this -> conn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this -> conn;
        } catch (PDOException $e) {
            die('Erro na conexão: ' . $e->getMessage());
        }
    }
}
