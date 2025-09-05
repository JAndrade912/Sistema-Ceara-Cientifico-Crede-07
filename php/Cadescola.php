<?php
require_once '../php/connect.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $nome = $_POST['nome'] ?? '';
}