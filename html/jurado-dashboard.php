<?php
session_start();

if(!isset($_SESSION['id_jurados']) || !isset($_SESSION['usuario'])){
  header('Location: ../html/login_jurado.php');
  exit();
}

$userName = $_SESSION['usuario']; 
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Jurado Dashboard</title>
  <link rel="stylesheet" href="../assets/styles/dashboard.css">
  <link rel="stylesheet" href="../boostrap/CSS/bootstrap.min.css">
  <script href="../boostrap/JS/bootstrap.bundle.min.js"></script>
  <script href="../boostrap/JS/jquery.min.js"></script>
</head>
<body>
  <header class="menu-superior">
    <h2>Jurado</h2>
  </header>
  <main class="conteudo">
    <h1>Lista de Trabalhos</h1>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Título</th>
          <th>Escola</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>1</td>
          <td>Trabalho de Matemática</td>
          <td>Escola A</td>
          <td>
            <button class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#modalInstituicao" style="background-color: #4C8F5A;">Avaliar</button>
</td>
            <div class="modal fade" id="modalInstituicao" tabindex="-1" aria-labelledby="modalInstituicaoLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalInstituicaoLabel">Cadastrar Instituição</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
          </div>
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
                <option value="0">Não Focalizado</option>
              </select>

              <div id="campo-ide" style="display:none;">
                <label for="instituicao-ide" class="form-label mt-2">IDE Médio da Escola</label>
                <input type="number" id="instituicao-ide" class="form-control" placeholder="Digite o IDE médio" name="ide">
              </div>

              <input type="submit" value="Enviar" class="btn btn-success mt-3">
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
          </div>
        </div>
      </div>
    </div>
          
        </tr>
      </tbody>
    </table>
  </main>
</body>
</html>