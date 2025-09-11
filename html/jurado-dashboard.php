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

  <!-- Scripts corretamente incluídos -->
  <script src="../boostrap/JS/jquery.min.js"></script>
  <script src="../boostrap/JS/bootstrap.bundle.min.js"></script>
</head>
<body>
  <header class="menu-superior" >
    <div style="display: flex;align-items: center;">
      <a href="../html/login_jurado.php" style="float: left;"><img src="../assets/img/sair.png" ></a>
      <img src="../assets/img/cearacientifico.png"style="width: 25%; margin-left: 77%">
    </div>
  </header>
  
  <main class="conteudo">
    <div style="text-align: center; width: 100%;"><h2>Lista de Trabalhos</h2></div>
    <br>
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
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#avaliarModal">Avaliar</button>
          </td>
        </tr>
      </tbody>
    </table>
    <br>
    <br>
  </main>

  <!-- Modal -->
  <div class="modal fade" id="avaliarModal" tabindex="-1" aria-labelledby="avaliarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">

        <div class="modal-header">
          <h5 class="modal-title" id="avaliarModalLabel">Avaliação do Trabalho</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>

        <div class="modal-body">
          <div class="container">
            <div class="mb-4">
              <p style="float: left;">
                <strong class="text">Título: Trabalho de Matematica</strong><br><br>
                <strong class="text">Escola: Escola A</strong>
              </p>
              <p style="float: right; margin-right: 10%;">
                <strong class="text">Categoria: Ensino Médio</strong><br><br>
                <strong class="text">Área: Matematica</strong>
              </p>
              <div style="clear: both;"></div>
            </div>

            <form action="#" method="post">
              <table class="table table-bordered">
                <thead class="table-light">
                  <tr>
                    <th>Critério</th>
                    <th>Avaliação</th>
                    <th>Comentário</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td><b>Criatividade e Inovação</b></td>
                    <td><input type="number" class="form-control" name="criterio1" min="0" max="10" required></td>
                    <td><input type="text" class="form-control" name="comentario1" ></td>
                  </tr>
                  <tr>
                    <td><b>Relevância da pesquisa</b></td>
                    <td><input type="number" class="form-control" name="criterio2" min="0" max="10" required></td>
                    <td><input type="text" class="form-control" name="comentario2" ></td>
                  </tr>
                  <tr>
                    <td><b>Conhecimento científico fundamentado e contextualização do problema abordado</b></td>
                    <td><input type="number" class="form-control" name="criterio3" min="0" max="10" required></td>
                    <td><input type="text" class="form-control" name="comentario3" ></td>
                  </tr>
                  <tr>
                    <td><b>Impacto para a construção de uma sociedade que promova os saberes científicos em tempos de crise climática global</b></td>
                    <td><input type="number" class="form-control" name="criterio4" min="0" max="10" required></td>
                    <td><input type="text" class="form-control" name="comentario4" ></td>
                  </tr>
                  <tr>
                    <td><b>Metodologia científica conectada com os objetivos, resultados e conclusões</b></td>
                    <td><input type="number" class="form-control" name="criterio5" min="0" max="10" required></td>
                    <td><input type="text" class="form-control" name="comentario5" ></td>
                  </tr>
                  <tr>
                    <td><b>Clareza e objetividade na linguagem apresentada</b></td>
                    <td><input type="number" class="form-control" name="criterio6" min="0" max="10" required></td>
                    <td><input type="text" class="form-control" name="comentario6" ></td>
                  </tr>
                  <tr>
                    <td><b>Banner</b></td>
                    <td><input type="number" class="form-control" name="criterio7" min="0" max="10" required></td>
                    <td><input type="text" class="form-control" name="comentario7" ></td>
                  </tr>
                  <tr>
                    <td><b>Caderno de campo</b></td>
                    <td><input type="number" class="form-control" name="criterio8" min="0" max="10" required></td>
                    <td><input type="text" class="form-control" name="comentario8" ></td>
                  </tr>
                  <tr>
                    <td><b>Processo participativo e solidário</b></td>
                    <td><input type="number" class="form-control" name="criterio9" min="0" max="10" required></td>
                    <td><input type="text" class="form-control" name="comentario9" ></td>
                  </tr>
                </tbody>
              </table>

              <div class="d-flex justify-content-between mt-4">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Voltar</button>
                <button type="submit" class="btn btn-success">Finalizar Avaliação</button>
              </div>
            </form>
          </div>
        </div>

      </div>
    </div>
  </div>
</body>
</html>
