<?php
require_once '../php/Connect.php';
$categoria = $_POST['categoria'] ?? null;
$area = $_POST['area'] ?? null;
$jurado = $_POST['jurado'] ?? null;
$trabalhos = $_POST['trabalhos'] ?? [];

if (!$categoria || !$jurado || empty($trabalhos)) {
    echo "Dados incompletos!";
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO Jurado_Trabalho (id_jurado,id_trabalho) VALUES (?, ?)");
    foreach ($trabalhos as $trabalhoId) {
        $stmt->execute([$jurado, $trabalhoId]);
    }

    echo "Associação feita com sucesso!";
    header('Location: ../html.admin-dashboard.php');
} catch (PDOException $e) {
    echo "Erro ao associar: " . $e->getMessage();
}
