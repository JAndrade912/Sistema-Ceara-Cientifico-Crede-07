<?php
require_once '../Connect.php';

$error = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    try{
        $stmt = $pdo -> prepare("INSERT INTO Administracao (usuario,senha) VALUES (':usuario',':senha')");
        $stmt -> execute([":usuario => $usuario, :senha => $senhaHash"]);

        header('Location: ../html/login_adm.php');
        exit();
    }catch(PDOException $e){
        if($e->errorInfo[1] == 1062){
            $error = 'Este usuário já existe';
        }else{
            $error = 'Erro ao tentar cadastrar' . $e -> getMessage();
        }
    }
}