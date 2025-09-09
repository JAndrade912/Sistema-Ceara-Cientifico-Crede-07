<?php
$pdo = new PDO("mysql:host=localhost;dbname=SACC","root","cr701201");

try {
    $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo -> setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $pdo -> exec("SET NAMES utf8");
} catch (PDOException $e) {
    echo "Não foi possível se conectar ao banco de dados: ". $e->getMessage() ."";
    asda
}

?>