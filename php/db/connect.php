<?php

    $host = "localhost";
    $usuario = "root";
    $senha = "cr701201";
    $db = "SACC";

    $conn = mysqli_connect($host,$usuario,$senha,$db);

    if($conn -> error){
        die("Não foi possível conectar ao banco de dados");
    }
?>