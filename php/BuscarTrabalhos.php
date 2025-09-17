<?php
require_once '../php/Connect.php';

if (!isset($_GET['categoria']) || !isset($_GET['area']) || !isset($_GET['jurado'])) {
  http_response_code(400);
  echo json_encode([]);
  exit;
}

$categoria = $_GET['categoria'];
$area = $_GET['area'];
$jurado = $_GET['jurado'];

$stmt = $pdo->prepare("
  SELECT t.id_trabalhos, t.titulo, a.nome_area
  FROM Trabalhos t
  INNER JOIN Areas a ON t.id_areas = a.id_area
  WHERE t.id_categoria = ? AND t.id_areas = ?
");
$stmt->execute([$categoria, $area]);
$trabalhos = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($trabalhos);
