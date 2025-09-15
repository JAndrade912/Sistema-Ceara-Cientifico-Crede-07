<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../php/Connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? null;
    $nome = $_POST['nome'] ?? null;
    $telefone = $_POST['telefone'] ?? null;
    $cpf = $_POST['cpf'] ?? null;
    $email = $_POST['email'] ?? null;
    $categoria = $_POST['categoria'] ?? null;
    $area = $_POST['area'] ?? null;

    if (in_array($categoria, ['1', '2', '4']) && empty($area)) {
        die('Para a categoria selecionada, a área é obrigatória.');
    }

    if ($categoria === '3') {
        $area = null;
    }

    if (empty($id) || empty($nome) || empty($cpf) || empty($telefone) || empty($email)) {
        die('Todos os campos obrigatórios devem ser preenchidos.');
    }

    try {
        // Buscar o id_contatos correspondente ao jurado
        $stmtContato = $pdo->prepare("SELECT id_contatos FROM Jurados WHERE id_jurados = ?");
        $stmtContato->execute([$id]);
        $jurado = $stmtContato->fetch(PDO::FETCH_ASSOC);

        if (!$jurado) {
            die('Jurado não encontrado.');
        }

        $id_contato = $jurado['id_contatos'];

        // Atualizar tabela Contatos
        $stmtUpdateContato = $pdo->prepare("UPDATE Contatos SET telefone = ?, email = ? WHERE id_contatos = ?");
        $stmtUpdateContato->execute([$telefone, $email, $id_contato]);

        // Atualizar tabela Jurados
        $stmtUpdateJurado = $pdo->prepare("UPDATE Jurados SET nome = ?, cpf = ?, id_categoria = ?, id_area = ? WHERE id_jurados = ?");
        $stmtUpdateJurado->execute([$nome, $cpf, $categoria, $area, $id]);

        header('Location: ../html/admin-jurados.php?msg=atualizado');
        exit();
    } catch (PDOException $e) {
        die('Erro ao atualizar jurado: ' . $e->getMessage());
    }
} else {
    die('Método inválido.');
}
