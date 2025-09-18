<?php
require_once '../php/Connect.php';

$categoria = $_GET['categoria'] ?? null;
$area = $_GET['area'] ?? null;

if (!$categoria) {
  http_response_code(400);
  echo json_encode([]);
  exit;
}

if ($categoria === '3') {
  $stmt = $pdo->prepare("
    SELECT j.id_jurados, j.nome 
    FROM Jurados j
    INNER JOIN Jurados_Categorias_Areas jca ON j.id_jurados = jca.id_jurados
    WHERE jca.id_categoria = ?
  ");
  $stmt->execute([$categoria]);
} else {
  if (!$area) {
    http_response_code(400);
    echo json_encode([]);
    exit;
  }
  $stmt = $pdo->prepare("
    SELECT j.id_jurados, j.nome 
    FROM Jurados j
    INNER JOIN Jurados_Categorias_Areas jca ON j.id_jurados = jca.id_jurados
    WHERE jca.id_categoria = ? AND jca.id_area = ?
  ");
  $stmt->execute([$categoria, $area]);
}

$jurados = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($jurados);
