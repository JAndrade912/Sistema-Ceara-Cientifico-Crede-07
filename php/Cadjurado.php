<?php
require_once '../php/Connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $cpf = $_POST['cpf'];
    $email = $_POST['email'];
    $id_categoria = $_POST['categoria'] ?? '';
    $id_area = $_POST['area'] ?? '';
    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("INSERT INTO Contatos (telefone, email) VALUES (?, ?)");
        $stmt->execute([$telefone, $email]);
        $id_contato = $pdo->lastInsertId();
        //gerador de senha
        function gerarSenha($tamanho = 6) {
            $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $senha = '';
            for ($i = 0; $i < $tamanho; $i++) {
                $index = rand(0, strlen($caracteres) - 1);
                $senha .= $caracteres[$index];
            }
            return $senha;
        }
        $senha_padrao  = gerarSenha(6);

        function gerarUsuario($tamanho = 6) {
            $caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $usuario = '';
            for ($i = 0; $i < $tamanho; $i++) {
                $index = rand(0, strlen($caracteres) - 1);
                $usuario .= $caracteres[$index];
            }
            return $usuario;
        }
        $usuario = gerarUsuario(6);

        $stmt = $pdo->prepare("INSERT INTO Jurados(nome,usuario, senha, cpf, id_contatos, id_categoria, id_area) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nome,$usuario, $senha_padrao,  $cpf, $id_contato, $id_categoria, $id_area]);
        $pdo->commit();
        header('Location: ../html/admin-dashboard.php?msg=sucesso');
        exit();
    } catch (PDOException $e) {
        $pdo->rollBack();
        die("Erro ao cadastrar jurado: " . $e->getMessage());
    }
}
