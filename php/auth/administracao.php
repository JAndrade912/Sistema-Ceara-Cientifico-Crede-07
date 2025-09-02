<?php

    require_once 'connect.php';

    class Admin {

        private $db;

        public function __construct() {
            $database = new database();
            $this -> db = $database -> connect();
            session_start();
        }

        public function login ($username,$password){
            $stmt = $this -> db -> prepare("SELECT * FROM Administracao WHERE username = ?");
            $stmt -> execute([$username]);
            if($stmt -> rowCount() === 1){
                $admin = $stmt -> fetch(PDO::FETCH_ASSOC);
                if(password_verify($password,$admin['password'])){
                    $_SESSION['admin'] = ['id' => $admin['id'], 'username' => $admin['username']];
                    return true; 
                }
            }
            return false;
        }

        public function isLoged(){
            return isset ($_SESSION['admin']);
        }

        public function logout(){
            unset($_SESSION['admin']);
            session_destroy();
        }
    }