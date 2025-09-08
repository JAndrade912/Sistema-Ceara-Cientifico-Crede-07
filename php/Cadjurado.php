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
    $categoria = $categoria[$id_categoria] ?? 'Desconhecida';
    $area = [
        '1' => 'Linguagens, Códigos e suas Tecnologias - LC',
        '2' => 'Matemática e suas Tecnologias - MT',
        '3' => 'Ciências da Natureza, Educação Ambiental e Engenharias - CN',
        '4' => 'Ciências Humanas e Sociais Aplicadas - CH',
        '5' => 'Robótica, Automação e Aplicação das TIC',
        '6' => 'Ensino Fundamental',
        '7' => 'Ensino Médio'
    ];
    $id_area = $_POST['area'] ?? null;
    $area = $area[$id_area] ?? 'Não informada';
    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("INSERT INTO Contatos (telefone, email) VALUES (?, ?)");
        $stmt->execute([$telefone, $email]);
        $id_contato = $pdo->lastInsertId();

        $senha_padrao  = '123456';
        $senha_hash = password_hash($senha_padrao, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO Jurados(nome, usuario, senha, cpf, id_contatos, id_categoria, id_area) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nome, $usuario, $senha_hash,  $cpf, $id_contato, $id_categoria, $id_area]);
        $pdo->commit();
        header('Location: ../html/admin-dashboard.php?msg=sucesso');
        exit();

    } catch (PDOException $e) {
        $pdo->rollBack();
        die("Erro ao cadastrar jurado: " . $e->getMessage());
    }
}
