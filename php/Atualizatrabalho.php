<?php
require_once 'Connect.php';

$id = $_POST['id'] ?? null;
$titulo = $_POST['titulo'] ?? '';
$escola = $_POST['escola'] ?? null;
$categoria = $_POST['categoria'] ?? null;
$area = $_POST['area'] ?? null;

if (!$id || !$titulo || !$escola || !$categoria) {
    die("Campos obrigatórios não preenchidos.");
}

try {
    $stmt = $pdo->prepare("
        UPDATE Trabalhos
        SET
            titulo = :titulo,
            id_escolas = :escola,
            id_categoria = :categoria,
            id_areas = :area
        WHERE id_trabalhos = :id
    ");

    $stmt->execute([
        ':titulo' => $titulo,
        ':observacoes' => $observacoes,
        ':escola' => $escola,
        ':categoria' => $categoria,
        ':area' => $area ?: null,
        ':id' => $id
    ]);

    header("Location: ../html/admin-trabalhos.php?status=sucesso");
    exit;

} catch (PDOException $e) {
    error_log("Erro ao atualizar trabalho: " . $e->getMessage());
    die("Erro ao atualizar o trabalho.");
}
