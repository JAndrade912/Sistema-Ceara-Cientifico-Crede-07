<?php
require_once '../php/Connect.php';

if (!isset($_GET['categoria']) || !isset($_GET['area'])) {
  http_response_code(400);
  echo json_encode([]);
  exit;
}

$categoria = $_GET['categoria'];
$area = $_GET['area'];

$stmt = $pdo->prepare("
  SELECT j.id_jurados, j.nome 
  FROM Jurados j
  INNER JOIN Jurados_Categorias_Areas jca ON j.id_jurados = jca.id_jurados
  WHERE jca.id_categoria = ? AND jca.id_area = ?
");
$stmt->execute([$categoria, $area]);
$jurados = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($jurados);
