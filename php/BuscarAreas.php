<?php
require_once '../php/Connect.php';

if (!isset($_GET['categoria'])) {
  http_response_code(400);
  echo json_encode([]);
  exit;
}

$categoria = $_GET['categoria'];

if (in_array($categoria, ['1', '2'])) {
  $stmt = $pdo->prepare("SELECT id_area, nome_area FROM Areas WHERE id_area NOT IN (6, 7)");
} elseif ($categoria == '4') {
  $stmt = $pdo->prepare("SELECT id_area, nome_area FROM Areas WHERE id_area IN (6, 7)");
} else {
  echo json_encode([]);
  exit;
}

$stmt->execute();
$areas = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($areas);
