<?php
if(!empty($_GET['id'])){
    require_once '../php/connect.php';
    $id = $_GET['id'] ?? null;
    
    $stmt = $pdo -> prepare("DELETE FROM Escolas WHERE id_escolas = ?");
    $stmt -> execute([$id]);
    header("Location: ../html/admin-escolas.php"); 
}