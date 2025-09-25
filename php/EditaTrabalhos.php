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

<body class="container mt-4">
    <h2>Editar Escola</h2>
            <div class="modal-body">
              <form action="../php/Cadtrabalho.php" method="POST" id="idCadTrabalho">
                <label for="titulo">Título do Trabalho</label>
                <input type="textarea" class="form-control" name="titulo" placeholder="Digite o nome do Trabalho" required>
                <label for="escola">Escola</label>
                <select name="escola" id="escola" class="form-control" required>
                  <option value="">Selecione a Escola</option>
                    </option>
                </select>
                <label for="trabalho-categoria">Categoria</label>
                <select id="trabalho-categoria" name="categoria" class="form-control" required>
                  <option value="">-- Selecione a Categoria --</option>
                    <option value="1">I - Ensino Médio</option>
                    <option value="2">II - Ensino Médio - Ações Afirmativas e CEJAs EM</option>
                    <option value="3">III - Pesquisa Júnior</option>
                    <option value="4">IV - PcD</option>
                </select>
                <div id="trabalho-area">
                  <label>Área</label>
                  <select name="area" class="form-control">
                    <option value="">-- Selecione a Área --</option>
                    <option value="1">Linguagens, Códigos e suas Tecnologias - LC</option>
                    <option value="2">Matemática e suas Tecnologias - MT</option>
                    <option value="3">Ciências da Natureza, Educação Ambiental e Engenharias - CN</option>
                    <option value="4">Ciências Humanas e Sociais Aplicadas - CH</option>
                    <option value="5">Robótica, Automação e Aplicação das TIC</option>
                  </select>
                </div>
                <div id="trabalho-area2">
                  <label>Área</label>
                  <select name="area" class="form-control">
                     <option value="">-- Selecione a Área --</option>
                    <option value="6">Ensino Fundamental</option>
                    <option value="7">Ensino Médio</option>
                  </select>
                </div>
            <input type="submit" value="Atualizar" class="btn btn-success mt-3" style="margin-top:10px;">
            <a href="../html/admin-trabalhos.php" class="btn btn-secondary mt-3">Cancelar</a>
        </form>
    </div>
    <div class="modal-footer">

    </div>
    </div>
    </div>
    </div>