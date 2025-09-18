<?php
require_once '../php/Connect.php';

$categoria = $_POST['categoria'] ?? null;
$area = $_POST['area'] ?? null;
$jurado = $_POST['jurado'] ?? null;
$trabalhos = $_POST['trabalhos'] ?? [];

if (!$categoria || !$jurado || ( ($categoria != '3') && empty($area) ) || empty($trabalhos)) {
    echo "Dados incompletos!";
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO Jurado_Trabalho (id_jurado,id_trabalho) VALUES (?, ?)");

    foreach ($trabalhos as $trabalhoId) {
        $stmt->execute([$jurado, $trabalhoId]);
    }

    header('Location: ../html/admin-dashboard.php');
    exit;
} catch (PDOException $e) {
    echo "Erro ao associar: " . $e->getMessage();
}
?>
