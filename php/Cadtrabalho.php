<?php
require_once '../php/Connect.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'];
    $observacoes = $_POST['observacoes'] ?? '.';
    $id_escolas = $_POST['escola'] ?? null;
    $id_jurados = $_POST['id_jurados'] ?? null;
    $id_area = $_POST['area'];
    $id_categoria = $_POST['categoria'] ?? null;
    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("INSERT INTO Trabalhos (titulo, observacoes, id_escolas, id_jurados, id_areas, id_categoria) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$titulo, $observacoes, $id_escolas, $id_jurados, $id_area, $id_categoria]);
        $pdo->commit();
        header('Location: ../html/admin-dashboard.php?msg=sucesso');
        exit();
    } catch (PDOException $e) {
        $pdo->rollBack();
        die("Erro ao cadastrar Trabalho: " . $e->getMessage());
    }
}
?>