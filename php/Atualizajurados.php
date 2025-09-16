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

    $categoria1 = $_POST['categoria1'] ?? null;
    $area1 = $_POST['area1'] ?? null;
    $categoria2 = $_POST['categoria2'] ?? null;
    $area2 = $_POST['area2'] ?? null;

    if (empty($id) || empty($nome) || empty($cpf) || empty($telefone) || empty($email)) {
        die('Todos os campos obrigatórios devem ser preenchidos.');
    }

    try {
        $pdo->beginTransaction();

        $stmtContato = $pdo->prepare("SELECT id_contatos FROM Jurados WHERE id_jurados = ?");
        $stmtContato->execute([$id]);
        $jurado = $stmtContato->fetch(PDO::FETCH_ASSOC);

        if (!$jurado) {
            die('Jurado não encontrado.');
        }

        $id_contato = $jurado['id_contatos'];

        $stmtUpdateContato = $pdo->prepare("UPDATE Contatos SET telefone = ?, email = ? WHERE id_contatos = ?");
        $stmtUpdateContato->execute([$telefone, $email, $id_contato]);

        $stmtUpdateJurado = $pdo->prepare("UPDATE Jurados SET nome = ?, cpf = ? WHERE id_jurados = ?");
        $stmtUpdateJurado->execute([$nome, $cpf, $id]);

        $stmtDeleteCategorias = $pdo->prepare("DELETE FROM Jurados_Categorias_Areas WHERE id_jurados = ?");
        $stmtDeleteCategorias->execute([$id]);

        if (!empty($categoria1)) {
            $stmtInsert = $pdo->prepare("INSERT INTO Jurados_Categorias_Areas (id_jurados, id_categoria, id_area) VALUES (?, ?, ?)");
            $stmtInsert->execute([$id, $categoria1, $area1 ?? null]);
        }

        if (!empty($categoria2)) {
            $stmtInsert = $pdo->prepare("INSERT INTO Jurados_Categorias_Areas (id_jurados, id_categoria, id_area) VALUES (?, ?, ?)");
            $stmtInsert->execute([$id, $categoria2, $area2 ?? null]);
        }

        $pdo->commit();

        header('Location: ../html/admin-jurados.php?msg=atualizado');
        exit();
    } catch (PDOException $e) {
        $pdo->rollBack();
        die('Erro ao atualizar jurado: ' . $e->getMessage());
    }
} else {
    die('Método inválido.');
}
