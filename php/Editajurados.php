<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Jurado</title>
    <link rel="stylesheet" href="../boostrap/CSS/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/styles/admin-dashboard.css">
    <script src="../boostrap/JS/bootstrap.bundle.min.js"></script>
    <script src="../boostrap/JS/jquery.min.js"></script>
    <link href="../boostrap/CSS/bootstrap.min.css" rel="stylesheet">
    <link href="../boostrap/CSS/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <style>
        .modal-body{
            margin: 40px;
        }
    </style>
    <div class="modal-header">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">
        <h5 class="modal-title" id="modalJuradoLabel">Editar Jurado</h5>
        <form action="../php/Cadjurado.php" method="POST" id="idCadJurado">
            <label for="nome">Nome</label>
            <input type="text" class="form-control" name="nome" placeholder="Digite seu nome" required>
            <label for="telefone">Telefone</label>
            <input type="text" class="form-control" name="telefone" placeholder="Digite seu telefone" required>
            <label for="cpf">CPF</label>
            <input type="text" class="form-control" name="cpf" placeholder="Digite seu CPF" required>
            <label for="email">E-mail SIC-CED</label>
            <input type="text" class="form-control" name="email" placeholder="Digite seu e-mail" required>
            <label for="id_categoria">Categoria</label>
            <select id="jurado-categoria" class="form-control" name="categoria" required>
                <option selected disabled>Selecione...</option>
                <option value="1">I - Ensino Médio</option>
                <option value="2">II - Ensino Médio - Ações Afirmativas e CEJAs EM</option>
                <option value="3">III - Pesquisa Júnior</option>
                <option value="4">IV - PcD</option>
            </select>
            <div id="jurado-area" style="display:none;">

                <label for="id_areas">Área</label>
                <select class="form-control" name="area">
                    <option selected disabled>Selecione...</option>
                    <option value="1">Linguagens, Códigos e suas Tecnologias - LC</option>
                    <option value="2">Matemática e suas Tecnologias - MT</option>
                    <option value="3">Ciências da Natureza, Educação Ambiental e Engenharias - CN</option>
                    <option value="4">Ciências Humanas e Sociais Aplicadas - CH</option>
                    <option value="5">Robótica, Automação e Aplicação das TIC</option>
                </select>
            </div>

            <div id="jurado-area2" style="display:none;">
                <label for="id_area">Área</label>
                <select class="form-control" name="area">
                    <option selected disabled>Selecione...</option>
                    <option value="6">Ensino Fundamental</option>
                    <option value="7">Ensino Médio</option>
                </select>
            </div>

            <input type="submit" value="Enviar" class="btn btn-success" style="margin-top:10px;">
        </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
    </div>
    </div>
    </div>
    </div>

    <div class="modal fade" id="modalTrabalho" tabindex="-1" aria-labelledby="modalTrabalhoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalJuradoLabel">Cadastrar Trabalho</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <form action="../php/Cadtrabalho.php" method="POST" id="idCadTrabalho">
                        <label for="titulo">Título do Trabalho</label>
                        <input type="text" class="form-control" name="titulo" placeholder="Digite o nome do Trabalho" required>
                        <label for="escola">Escola</label>
                        <select name="escola" id="escola" class="form-control" required>
                            <option value="">Selecione a Escola</option>
                            <?php foreach ($escolas as $escola): ?>
                                <option value="<?= htmlspecialchars($escola['id_escolas']) ?>">
                                    <?= htmlspecialchars($escola['nome']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <label for="trabalho-categoria">Categoria</label>
                        <select id="trabalho-categoria" name="categoria" class="form-control" required>

                            <option selected disabled>Selecione...</option>
                            <?php foreach ($categorias as $categoria): ?>
                                <option value="<?= htmlspecialchars($categoria['id_categoria']) ?>">
                                    <?= htmlspecialchars($categoria['nome_categoria']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <div id="trabalho-area" style="display:none;">
                            <label>Área</label>


                            <select name="area" class="form-control">
                                <option selected disabled>Selecione...</option>
                                <?php foreach ($areas as $area): ?>
                                    <option value="<?= htmlspecialchars($area['id_area']) ?>">
                                        <?= htmlspecialchars($area['nome_area']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div id="trabalho-area2" style="display:none;">
                            <label>Área</label>
                            <select class="form-control">
                                <option selected disabled>Selecione...</option>
                                <option value="1">Ensino Fundamental</option>
                                <option value="2">Ensino Médio</option>
                            </select>
                        </div>
                        <input type="submit" value="Enviar" class="btn btn-success" style="margin-top:10px;">
                    </form>
                </div>
                <script>
                    // Lógica dos modais e exibição de áreas
                    $('#instituicao-tipo').change(function() {
                        ($(this).val() === '1') ? $('#campo-ide').slideDown(): $('#campo-ide').slideUp();
                    });
                    $('#jurado-categoria').change(function() {
                        var categoria = $(this).val();
                        if (categoria === '1' || categoria === '2') {
                            $('#jurado-area').slideDown();
                            $('#jurado-area2').slideUp();
                        } else if (categoria === '4') {
                            $('#jurado-area').slideUp();
                            $('#jurado-area2').slideDown();
                        } else {
                            $('#jurado-area, #jurado-area2').slideUp();
                        }
                    });
                    $('#trabalho-categoria').change(function() {
                        var categoria = $(this).val();
                        if (categoria === '1' || categoria === '2') {
                            $('#trabalho-area').slideDown();
                            $('#trabalho-area2').slideUp();
                        } else if (categoria === '4') {
                            $('#trabalho-area').slideUp();
                            $('#trabalho-area2').slideDown();
                        } else {
                            $('#trabalho-area, #trabalho-area2').slideUp();
                        }
                    });

                    $('#associar-categoria').change(function() {
                        var categoria = $(this).val();
                        if (categoria === '1' || categoria === '2') {
                            $('#areajurado').slideDown();
                            $('#area2jurado').slideUp();
                        } else if (categoria === '4') {
                            $('#area2jurado').slideDown();
                            $('#areajurado').slideUp();
                        } else if (categoria === '3') {
                            $('#trabalhojurado').slideDown();
                            $('#areajurado, #area2jurado').slideUp();
                        }
                    });
                    $('#area1, #area2').change(function() {
                        $('#trabalhojurado').slideDown();
                    });
                </script>
</body>
</html>