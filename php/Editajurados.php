<?php
require_once '../php/connect.php';
$id = $_GET['id'] ?? null;
if (!$id) {
    echo 'ID não fornecido';
}

$stmt = $pdo->prepare("SELECT * FROM Jurados WHERE id_jurados = ?");
$stmt->execute([$id]);
$escola = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$escola) {
    echo 'Jurado não encontrado!';
}
?>
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
        .modal-body {
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
            <input type="text" class="form-control" name="nome" placeholder="Digite seu nome" value="<?= htmlspecialchars($nome['nome']) ?>" required>
            <label for="telefone">Telefone</label>
            <input type="text" class="form-control" name="telefone" placeholder="Digite seu telefone" value="<?= htmlspecialchars($telefone['telefone']) ?>" required>
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
        
    </div>
    </div>
    </div>
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