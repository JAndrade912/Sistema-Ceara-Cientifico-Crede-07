<?php
require_once '../php/connect.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    
    $nome = $_POST['nome'] ?? '';
    $focalizado = ['1' => 'Focalizada'];
    $idFocalizado = $_POST['focalizada'] ?? null;
    $focalizado = $focalizado[$idFocalizado] ?? null;
    $ide = ['1' => 'Sim'];
    $idIde = $_POST['ide'] ?? null;
    $ide = $ide[$idIde] ?? null;
    $municipio = ['1' => 'Caridade', '2' => 'Canindé', '3' => 'Paramoti', '4' => 'General Sampaio', '5' => 'Santa Quitéria', '6' => 'Itatira'];
    $idMunicipio = $_POST['municipio'] ?? null;
    $municipio = $municipio[$idMunicipio] ?? 'Desconhecido';

    if(empty($nome)){
        die('Nome da escola é obrigatório!');
    }

    $stmt = $pdo -> prepare("INSERT INTO Escolas (nome, focalizada, ide, municipio) VALUES (?, ?, ?, ?)");
    try{
        $stmt -> execute([$nome,$focalizado,$ide,$municipio]);
        header('Location: ../html/admin-dashboard.php?msg=sucesso');
        exit();
    }catch(PDOException $e){
        die('Erro ao cadastrar escola' . $e -> getMessage());
    }
}