<?php
require_once '../php/connect.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $nome = $_POST['nome'] ?? '';
    $focalizado = isset($_POST['focalizada']) ? (int)$_POST['focalizada'] : 0;
    $ide = $_POST['ide'] ?? null;
    $municipio = $_POST['municipio'] ?? 'Desconhecido';

    if(empty($nome)){
        die('Nome da escola é obrigatório!');
    }

    $stmt = $pdo -> prepare("INSERT INTO Escolas (nome, focalizada, ide, municipio) VALUES (?, ?, ?, ?)");
    try{
        $stmt -> execute([$nome,$focalizado,$ide,$municipio]);
    }catch(PDOException $e){
        die('Erro ao cadastrar escola' . $e -> getMessage());
    }
}