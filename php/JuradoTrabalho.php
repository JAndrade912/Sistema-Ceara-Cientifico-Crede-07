<?php
require_once '../php/Connect.php';

$idTrabalho = $_POST['id_trabalho'] ?? null;

if (!$idTrabalho) {
    http_response_code(400);
    echo json_encode([]);
    exit;
}

$stmt = $pdo->prepare("
  SELECT DISTINCT j.id_jurados AS id_jurado, j.nome
  FROM Avaliacoes a
  INNER JOIN Jurados j ON a.id_jurado = j.id_jurados
  WHERE a.id_trabalho = :idTrabalho
");

$stmt->execute(['idTrabalho' => $idTrabalho]);
$jurados = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($jurados);
