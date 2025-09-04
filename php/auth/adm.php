<?php

session_start();
require_once 'php/db/connect.php';    

$error = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    try{
        $stmt = $pdo -> prepare("SELECT * FROM Administracao WHERE usuario = :usuario LIMIT 1");
        $stmt -> execute([':usuario' => $usuario]);
        $user = $stmt -> fetch(PDO::FETCH_ASSOC);

        if($user && password_verify($senha,$user['senha'])){
            $_SESSION['id_admin'] = $user['id'];
            $_SESSION['usuario'] = $user['usuario'];
            $_SESSION['senha'] = $user['senha'];

            header('Location: html/dashboards/admin/dashboard_admin.html');
            exit();
        }else{
            $error = 'Email e/ou senha errados!';    
        }

    }catch(PDOException $e){
        $error = 'Erro ao tentar fazer login' . $e -> getMessage();
    }
}