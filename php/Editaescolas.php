<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Escolas</title>
    <link href="../boostrap/CSS/bootstrap.min.css" rel="stylesheet">
    <script src="../boostrap/JS/bootstrap.bundle.min.js"></script>
    <link href="../boostrap/CSS/bootstrap-icons.css" rel="stylesheet">
    <script src="../boostrap/JS/jquery.min.js"></script>
    <link rel="stylesheet" href="../assets/styles/dashboard-admin.css">
</head>

<body>
    <div class="modal-body">
        <form method="POST" action="../php/Cadescola.php" id="idCadEscola">
            <label for="instituicao-nome" class="form-label">Nome da Instituição</label>
            <input type="text" id="instituicao-nome" class="form-control" name="nome" placeholder="Digite o nome da instituição" required>

            <label for="instituicao-localidade" class="form-label mt-2">Localidade</label>
            <select id="instituicao-localidade" class="form-control" name="municipio">
                <option selected disabled>Selecione...</option>
                <option value="1">Caridade</option>
                <option value="2">Canindé</option>
                <option value="3">Paramoti</option>
                <option value="4">General Sampaio</option>
                <option value="5">Santa Quitéria</option>
                <option value="6">Itatira</option>
            </select>

            <label for="instituicao-tipo" class="form-label mt-2">Tipo</label>
            <select id="instituicao-tipo" class="form-control" name="focalizada">
                <option selected disabled>Selecione...</option>
                <option value="1">Focalizado</option>
            </select>

            <div id="campo-ide" style="display:none;">
                <label for="instituicao-ide" class="form-label mt-2">IDE Médio da Escola</label>
                <select id="instituicao-ide" class="form-control" name="ide">
                    <option selected disabled>Selecione...</option>
                    <option value="1">Sim</option>
                </select>
            </div>

            <input type="submit" value="Enviar" class="btn btn-success mt-3">
        </form>
    </div>
</body>

</html>