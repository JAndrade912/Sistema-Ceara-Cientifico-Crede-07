<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(!empty($_GET['id'])){
    require_once '../php/Connect.php';
    $id = $_GET['id'] ?? null;
    
    $stmt1 = $pdo->prepare("DELETE FROM Avaliacoes WHERE id_trabalho = ?");
    $stmt1->execute([$id]);

    $stmt2 = $pdo->prepare("DELETE FROM Trabalhos WHERE id_trabalhos = ?");
    $stmt2->execute([$id]);
    
    header("Location: ../html/admin-trabalhos.php");
}
?>