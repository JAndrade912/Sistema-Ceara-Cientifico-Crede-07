<?php

session_start();

require_once '../db/connect.php';    

$usuario = $_POST['usuario'] ?? '';
$password = $_POST['password'] ?? '';

$sql = "SELECT id, senha FROM Administracao WHERE usuario = :usuario LIMIT 1";
$stmt = $pdo -> prepare($sql);
$stmt -> bindParam(":usuario", $usuario);
$stmt -> execute();

if($stmt -> rowCount() > 0){
    $dados = $stmt -> fetch(PDO::FETCH_ASSOC);

    if(password_verify($senha, $dados["password"])){
        $_SESSION['usuario_id'] = $dados['id'];
        $_SESSION['usuario_nome'] = $usuario;
        header('Location: ../dashboards/admin/dashboard_admin.html');
        exit();
    }else{
       $_SESSION['erro_login'] = 'Senha incorreta!';
       header('Location: ../php/auth/login_adm.php');
       exit(); 
    }
}else{
    $_SESSION['erro_login'] = "Usuário não encontrado!";
    header('Location: ../php/auth/login_adm.php');
    exit();
}

?>