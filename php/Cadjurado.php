<?php
require_once '../php/Connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $cpf = $_POST['cpf'];
    $email = $_POST['email'];

    $categorias = $_POST['categoria'] ?? [];
    $areas = $_POST['area'] ?? [];

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("INSERT INTO Contatos (telefone, email) VALUES (?, ?)");
        $stmt->execute([$telefone, $email]);
        $id_contato = $pdo->lastInsertId();

        // Gerar senha e usuário
        function gerarSenha($tamanho = 6)
        {
            $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            return substr(str_shuffle(str_repeat($caracteres, $tamanho)), 0, $tamanho);
        }

        function gerarUsuario($tamanho = 6)
        {
            $caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            return substr(str_shuffle(str_repeat($caracteres, $tamanho)), 0, $tamanho);
        }

        $senha_padrao = gerarSenha();
        $usuario = gerarUsuario();

        $stmt = $pdo->prepare("INSERT INTO Jurados (nome, usuario, senha, cpf, id_contatos) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nome, $usuario, $senha_padrao, $cpf, $id_contato]);
        $id_jurado = $pdo->lastInsertId();

        $stmtAssoc = $pdo->prepare("INSERT INTO Jurados_Categorias_Areas (id_jurados, id_categoria, id_area) VALUES (?, ?, ?)");

        $pesquisaJuniorId = 3; // id da categoria Pesquisa Júnior

        for ($i = 0; $i < 2; $i++) {
            $id_categoria = $categorias[$i] ?? null;
            $id_area = $areas[$i] ?? null;

            if ($id_categoria) {
                if ($id_categoria == $pesquisaJuniorId) {
                    $stmtAssoc->execute([$id_jurado, $id_categoria, $id_area]);
                } else {
                    if ($id_area) {
                        $stmtAssoc->execute([$id_jurado, $id_categoria, $id_area]);
                    } else {
                        throw new Exception("Área é obrigatória para categoria diferente de Pesquisa Júnior.");
                    }
                }
            }
        }
        $pdo->commit();
        header('Location: ../html/admin-dashboard.php?msg=sucesso');
        exit();
    } catch (PDOException $e) {
        $pdo->rollBack();
        die("Erro ao cadastrar jurado: " . $e->getMessage());
    }
}
