<?php
require_once '../php/connect.php';
$id = $_GET['id'] ?? null;
if (!$id) {
    echo 'ID não fornecido';
}

$stmt = $pdo->prepare("SELECT * FROM Escolas WHERE id_escolas = ?");
$stmt->execute([$id]);
$trabalho = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$trabalho) {
    echo 'Trabalho não encontrado';
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Editar Trabalho</title>
    <link href="../boostrap/CSS/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-4">
    <h2>Editar Trabalho</h2>

    <form method="POST" action="AtualizaTrabalhos.php">
        <input type="hidden" name="id" value="<?php $trabalho['id_trabalhos'] ?>">

        <label for="titulo" class="form-label">Título do Trabalho</label>
        <input type="text" name="titulo" id="titulo" class="form-control" value="<?php htmlspecialchars($titulo['titulo']) ?>" required>
        
        <label for="escola" class="form-label">Nome da Instituição</label>
        <input type="text" name="escola" id="escola" class="form-control" value="<?php htmlspecialchars($escola['nome']) ?>" required>

        <label class="form-label mt-2">Tipo</label>
        <select name="focalizada" class="form-control">
            <option value="" selected> Selecione... </option>
            <option value="1">Focalizada</option>
        </select>

        <label class="form-label mt-2">IDE Médio</label>
        <select name="ide" class="form-control">
            <option value="" selected> Selecione... </option>
            <option value="1">Sim</option>
        </select>

        <input type="submit" value="Atualizar" class="btn btn-success mt-3">
        <a href="../html/admin-escolas.php" class="btn btn-secondary mt-3">Cancelar</a>
    </form>
</body>

</html>