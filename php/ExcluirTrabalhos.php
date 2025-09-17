<?php
if(!empty($_GET['id'])){
    require_once '../php/Connect.php';
    $id = $_GET['id'] ?? null;
    
    $stmt = $pdo -> prepare("DELETE FROM Trabalhos WHERE id_trabalhos = ?");
    $stmt -> execute([$id]);
    header("Location: ../html/admin-trabalhos.php");
}
?>