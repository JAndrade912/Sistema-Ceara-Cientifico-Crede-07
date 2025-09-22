<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../php/Connect.php';

if (!isset($_SESSION['id_jurados'])) {
    die("Acesso negado.");
}

$id_jurado = $_SESSION['id_jurados'];
$id_trabalho = $_POST['id_trabalho'] ?? null;

if (!$id_trabalho) {
    die("ID do trabalho inválido.");
}

try {
    $pdo->beginTransaction();

    for ($i = 1; $i <= 9; $i++) {
        $nota = isset($_POST["criterio$i"]) ? floatval(str_replace(',', '.', $_POST["criterio$i"])) : null;
        $comentario = $_POST["comentario$i"] ?? null;

        if ($nota === null || $nota < 0 || $nota > 10) {
            throw new Exception("Nota inválida no critério $i");
        }

        $stmt = $pdo->prepare("
            INSERT INTO Avaliacoes (id_trabalho, id_jurado, criterio, nota, comentario)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$id_trabalho, $id_jurado, $i, $nota, $comentario]);
    }

    $pdo->commit();
    header("Location: ../html/jurado-dashboard.php?msg=avaliado");
    exit();

} catch (Exception $e) {
    $pdo->rollBack();
    die("Erro ao salvar avaliação: " . $e->getMessage());
}
?>
