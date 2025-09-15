<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../php/Connect.php';
$id = $_GET['id'] ?? null;
if (!$id) {
    echo 'ID não fornecido';
}
$stmt = $pdo->prepare("SELECT 
    j.id_jurados,
    j.nome,
    j.usuario,
    j.cpf,
    j.id_categoria AS id_categoria,
    j.id_area AS id_area,
    c.nome_categoria,
    a.nome_area,
    co.telefone,
    co.email
FROM Jurados j
LEFT JOIN Contatos co ON j.id_contatos = co.id_contatos
LEFT JOIN Categorias c ON j.id_categoria = c.id_categoria
LEFT JOIN Areas a ON j.id_area = a.id_area
WHERE j.id_jurados = ?");

$stmt->execute([$id]);
$jurado = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$jurado) {
    echo 'Jurado não encontrado!';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

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
        <form action="../php/Atualizajurados.php" method="POST" id="idCadJurado">
            <input type="hidden" name="id" value="<?= $jurado['id_jurados'] ?>">
            <label for="nome">Nome</label>
            <input type="text" class="form-control" name="nome"
                placeholder="Digite seu nome"
                value="<?= htmlspecialchars($jurado['nome'] ?? '') ?>" required>

            <label for="telefone">Telefone</label>
            <input type="text" class="form-control" name="telefone"
                placeholder="Digite seu telefone"
                value="<?= htmlspecialchars($jurado['telefone'] ?? '') ?>" required>

            <label for="cpf">CPF</label>
            <input type="text" class="form-control" name="cpf"
                placeholder="Digite seu CPF"
                value="<?= htmlspecialchars($jurado['cpf'] ?? '') ?>" required>

            <label for="email">E-mail SIC-CED</label>
            <input type="text" class="form-control" name="email"
                placeholder="Digite seu e-mail"
                value="<?= htmlspecialchars($jurado['email'] ?? '') ?>" required>
            <label for="id_categoria">Categoria</label>
            <select id="jurado-categoria" class="form-control" name="categoria" required>
                <option disabled <?= !isset($jurado['id_categoria']) ? 'selected' : '' ?>>Selecione...</option>
                <option value="1" <?= ($jurado['id_categoria'] ?? '') == 1 ? 'selected' : '' ?>>I - Ensino Médio</option>
                <option value="2" <?= ($jurado['id_categoria'] ?? '') == 2 ? 'selected' : '' ?>>II - Ensino Médio - Ações Afirmativas e CEJAs EM</option>
                <option value="3" <?= ($jurado['id_categoria'] ?? '') == 3 ? 'selected' : '' ?>>III - Pesquisa Júnior</option>
                <option value="4" <?= ($jurado['id_categoria'] ?? '') == 4 ? 'selected' : '' ?>>IV - PcD</option>
            </select>

            <div id="jurado-area" style="display:none;">
                <label for="id_areas">Área</label>
                <select class="form-control" name="area1">
                    <option disabled <?= !isset($jurado['id_area']) || ($jurado['id_area'] < 1 || $jurado['id_area'] > 5) ? 'selected' : '' ?>>Selecione...</option>
                    <option value="1" <?= ($jurado['id_area'] ?? '') == 1 ? 'selected' : '' ?>>Linguagens, Códigos e suas Tecnologias - LC</option>
                    <option value="2" <?= ($jurado['id_area'] ?? '') == 2 ? 'selected' : '' ?>>Matemática e suas Tecnologias - MT</option>
                    <option value="3" <?= ($jurado['id_area'] ?? '') == 3 ? 'selected' : '' ?>>Ciências da Natureza, Educação Ambiental e Engenharias - CN</option>
                    <option value="4" <?= ($jurado['id_area'] ?? '') == 4 ? 'selected' : '' ?>>Ciências Humanas e Sociais Aplicadas - CH</option>
                    <option value="5" <?= ($jurado['id_area'] ?? '') == 5 ? 'selected' : '' ?>>Robótica, Automação e Aplicação das TIC</option>
                </select>
            </div>

            <div id="jurado-area" style="display:none;">
                <label for="id_areas">Área</label>
                <select class="form-control" name="area1">
                    <option disabled <?= !isset($jurado['id_area']) || ($jurado['id_area'] < 1 || $jurado['id_area'] > 5) ? 'selected' : '' ?>>Selecione...</option>
                    <option value="1" <?= ($jurado['id_area'] ?? '') == 1 ? 'selected' : '' ?>>Linguagens, Códigos e suas Tecnologias - LC</option>
                    <option value="2" <?= ($jurado['id_area'] ?? '') == 2 ? 'selected' : '' ?>>Matemática e suas Tecnologias - MT</option>
                    <option value="3" <?= ($jurado['id_area'] ?? '') == 3 ? 'selected' : '' ?>>Ciências da Natureza, Educação Ambiental e Engenharias - CN</option>
                    <option value="4" <?= ($jurado['id_area'] ?? '') == 4 ? 'selected' : '' ?>>Ciências Humanas e Sociais Aplicadas - CH</option>
                    <option value="5" <?= ($jurado['id_area'] ?? '') == 5 ? 'selected' : '' ?>>Robótica, Automação e Aplicação das TIC</option>
                </select>
            </div>

            <div id="jurado-area2" style="display:none;">
                <label for="id_area">Área</label>
                <select class="form-control" name="area2">
                    <option disabled <?= !isset($jurado['id_area']) || ($jurado['id_area'] < 6 || $jurado['id_area'] > 7) ? 'selected' : '' ?>>Selecione...</option>
                    <option value="6" <?= ($jurado['id_area'] ?? '') == 6 ? 'selected' : '' ?>>Ensino Fundamental</option>
                    <option value="7" <?= ($jurado['id_area'] ?? '') == 7 ? 'selected' : '' ?>>Ensino Médio</option>
                </select>
            </div>


            <input type="submit" value="Atualizar" class="btn btn-success" style="margin-top:10px;">
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
        $('#idCadJurado').submit(function(e) {
    var categoria = $('#jurado-categoria').val();
    var area = null;

    if (categoria === '1' || categoria === '2') {
        area = $('select[name="area1"]').val();
    } else if (categoria === '4') {
        area = $('select[name="area2"]').val();
    } else if (categoria === '3') {
        // Pesquisa Júnior - não tem área, então area fica null
        area = null;
    }

    if ((categoria === '1' || categoria === '2' || categoria === '4') && !area) {
        e.preventDefault();
        alert('Por favor, selecione uma área para essa categoria.');
        return false;
    }

    $('#area-hidden').remove();

    if (area !== null) {
        $('<input>').attr({
            type: 'hidden',
            id: 'area-hidden',
            name: 'area',
            value: area
        }).appendTo('#idCadJurado');
    }
});

    </script>
</body>

</html>