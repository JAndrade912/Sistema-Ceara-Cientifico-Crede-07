<?php
require_once '../php/connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $usuario = $_POST['usuario'];
    $telefone = $_POST['telefone'];
    $cpf = $_POST['cpf'];
    $email = $_POST['email'];
    $id_categoria = $_POST['categoria'] ?? null;
    $id_area = $_POST['area'] ?? null;
    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("INSERT INTO Contatos (telefone, email) VALUES (?, ?)");
        $stmt->execute([$telefone, $email]);
        $id_contato = $pdo->lastInsertId();

        $senha_padrao  = '123456';

        $stmt = $pdo->prepare("INSERT INTO Jurados(nome, usuario, senha, cpf, id_contatos, id_categoria, id_area) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nome, $usuario, $senha_padrao,  $cpf, $id_contato, $id_categoria, $id_area]);
        $pdo->commit();
        header('Location: ../html/admin-dashboard.php?msg=sucesso');
        exit();
    } catch (PDOException $e) {
        $pdo->rollBack();
        die("Erro ao cadastrar jurado: " . $e->getMessage());
    }
}
