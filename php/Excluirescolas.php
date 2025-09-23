<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(!empty($_GET['id'])){
    require_once '../php/Connect.php';
    $id = $_GET['id'] ?? null;
    
    $stmt = $pdo -> prepare("DELETE FROM Escolas WHERE id_escolas = ?");
    $stmt -> execute([$id]);
    header("Location: ../html/admin-escolas.php");
}
?>