<?php
require_once '../php/Connect.php';

$categoria = $_GET['categoria'] ?? null;
$area = $_GET['area'] ?? null;
$jurado = $_GET['jurado'] ?? null;

if (!$categoria || !$jurado) {
  http_response_code(400);
  echo json_encode([]);
  exit;
}

if ($categoria === '3') {
  $stmt = $pdo->prepare("
    SELECT t.id_trabalhos, t.titulo, a.nome_area
    FROM Trabalhos t
    INNER JOIN Areas a ON t.id_areas = a.id_area
    WHERE t.id_categoria = ?
  ");
  $stmt->execute([$categoria]);
} else {
  if (!$area) {
    http_response_code(400);
    echo json_encode([]);
    exit;
  }
  $stmt = $pdo->prepare("
    SELECT t.id_trabalhos, t.titulo, a.nome_area
    FROM Trabalhos t
    INNER JOIN Areas a ON t.id_areas = a.id_area
    WHERE t.id_categoria = ? AND t.id_areas = ?
  ");
  $stmt->execute([$categoria, $area]);
}

$trabalhos = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($trabalhos);
