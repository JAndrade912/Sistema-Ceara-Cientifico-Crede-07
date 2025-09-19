<?php
require_once '../php/Connect.php';

header('Content-Type: application/json');

$categoria = $_GET['categoria'] ?? null;
$area = $_GET['area'] ?? null;
$jurado = $_GET['jurado'] ?? null;

if (!$categoria || !$jurado) {
  http_response_code(400);
  echo json_encode([]);
  exit;
}

try {
  if ($categoria === '3') {
    $stmt = $pdo->prepare("
    SELECT t.id_trabalhos, t.titulo, a.nome_area
    FROM Trabalhos t
    LEFT JOIN Areas a ON t.id_areas = a.id_area
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
        AND t.id_trabalhos NOT IN (
          SELECT id_trabalho FROM Jurado_Trabalho WHERE id_jurado = ?
        )
    ");
    $stmt->execute([$categoria, $area, $jurado]);
  }

  $trabalhos = $stmt->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($trabalhos);
} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode(['error' => 'Erro ao buscar trabalhos: ' . $e->getMessage()]);
}
