<?php
$categoria = $_POST['categoria'] ?? null;
$area = $_POST['area'] ?? null;
$jurado = $_POST['jurado'] ?? null;
$trabalhos = $_POST['trabalhos'] ?? [];

if (!$categoria || !$jurado || empty($trabalhos)) {
    echo "Dados incompletos!";
    exit;
}

try {
    $pdo = new PDO('mysql:host=localhost;dbname=seubanco', 'usuario', 'senha');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("INSERT INTO Jurado_Trabalho (jurado_id, trabalho_id) VALUES (?, ?)");
    foreach ($trabalhos as $trabalhoId) {
        $stmt->execute([$jurado, $trabalhoId]);
    }

    echo "Associação feita com sucesso!";
} catch (PDOException $e) {
    echo "Erro ao associar: " . $e->getMessage();
}
