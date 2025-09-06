<?php
require_once '../php/connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome']; // tabela Jurados
    $usuario = $_POST['usuario']; // tabela Jurados
    $telefone = $_POST['telefone']; // tabela Contatos
    $cpf = $_POST['cpf']; // tabela Jurados
    $email = $_POST['email']; // tabela Contatos
    $categoria = ['1' => 'Ensino Médio', '2' => 'Ensino Médio - Ações Afirmativas e CEJAs EM', '3' => 'Pesquisa Júnior', '4' => 'PcD'];
    $id_categoria = $_POST['categoria'] ?? null;
    $categoria = $categoria[$id_categoria] ?? 'Descohecida';
    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("INSERT INTO Contatos (telefone, email) VALUES (?, ?)");
        $stmt->execute([$telefone, $email]);
        $id_contato = $pdo->lastInsertId();

        $senha_padrao  = '123456';
        $senha_hash = password_hash($senha_padrao, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO Jurados(nome, usuario, senha, cpf, id_contatos, id_categoria) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nome, $usuario, $senha_hash,  $cpf, $id_contato, $id_categoria]);
        $pdo->commit();
/*

CREATE TABLE Jurados (
    id_jurados INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(45) NOT NULL,
    usuario VARCHAR(45) NOT NULL,
    senha VARCHAR(255) NOT NULL,
    cpf VARCHAR(14) NOT NULL,
    id_contatos INT NULL,
    id_categoria INT NULL
);

*/
        echo "Cadastro realizado com Sucesso!";

    } catch (PDOException $e) {
        $pdo->rollBack();
        die("Erro ao cadastrar jurado: " . $e->getMessage());
    }
}
?>