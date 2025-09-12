<?php
session_start();
require_once '../php/Connect.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM Administracao WHERE usuario = :usuario LIMIT 1");
        $stmt->execute([':usuario' => $usuario]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && $senha == $user['senha']) {
            $_SESSION['id_admin'] = $user['id_admin'];
            $_SESSION['usuario'] = $user['usuario'];
            $_SESSION['senha'] = $user['senha'];

            header('Location: ../html/admin-dashboard.php');
            exit();
        } else {
            $_SESSION['login_error'] = 'Email e/ou senha errados!';
            header('Location: ../html/login_adm.php');
            exit();
        }
    } catch (PDOException $e) {
        $error = 'Erro ao tentar fazer login' . $e->getMessage();
    }
}
