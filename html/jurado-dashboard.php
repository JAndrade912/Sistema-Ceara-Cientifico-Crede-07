<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if (!isset($_SESSION['id_jurados']) || !isset($_SESSION['usuario'])) {
  header('Location: ../html/login_jurado.php');
  exit();
}

require_once '../php/Connect.php';

$idJurado = $_SESSION['id_jurados'];

$stmtUser = $pdo->prepare("SELECT nome FROM Jurados WHERE id_jurados = ?");
$stmtUser->execute([$idJurado]);
$result = $stmtUser->fetch(PDO::FETCH_ASSOC);
$userName = $result ? $result['nome'] : 'Usuário';

$stmt = $pdo->prepare("
  SELECT 
    t.id_trabalhos, 
    t.titulo, 
    e.nome AS nome_escola, 
    c.nome_categoria, 
    a.nome_area
  FROM Jurado_Trabalho jt
  INNER JOIN Trabalhos t ON jt.id_trabalho = t.id_trabalhos
  LEFT JOIN Escolas e ON t.id_escolas = e.id_escolas
  LEFT JOIN Categorias c ON t.id_categoria = c.id_categoria
  LEFT JOIN Areas a ON t.id_areas = a.id_area
  WHERE jt.id_jurado = ?
");
$stmt->execute([$idJurado]);
$trabalhos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Jurado Dashboard</title>
  <link rel="stylesheet" href="../assets/styles/dashboard.css" />
  <link rel="stylesheet" href="../boostrap/CSS/bootstrap.min.css" />
  <script src="../boostrap/JS/jquery.min.js"></script>
  <script src="../boostrap/JS/bootstrap.bundle.min.js"></script>
  <style>
    .is-invalid {
      border-color: #dc3545;
    }
  </style>

</head>

<body>
  <header class="menu-superior">
    <div style="display: flex; align-items: center;">
      <a href="../html/login_jurado.php" style="float: left;">
        <img src="../assets/img/sair.png" alt="Sair" />
      </a>
      <img src="../assets/img/cearacientifico.png" style="width: 25%; margin-left: 77%" alt="Logo Ceará Científico" />
    </div>
  </header>

  <main class="conteudo">
    <div style="text-align: center; width: 100%;">
      <h2>Lista de Trabalhos para <?= htmlspecialchars($userName) ?></h2>
    </div>
    <br />
    <table class="table table-striped table-bordered">
      <thead class="table-light">
        <tr>
          <th>Título</th>
          <th>Escola</th>
          <th>Categoria</th>
          <th>Área</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($trabalhos) === 0): ?>
          <tr>
            <td colspan="6" style="text-align:center;">Nenhum trabalho associado.</td>
          </tr>
        <?php else: ?>
          <?php foreach ($trabalhos as $trabalho): ?>
            <tr>
              <td><?= htmlspecialchars($trabalho['titulo']) ?></td>
              <td><?= htmlspecialchars($trabalho['nome_escola'] ?? 'N/D') ?></td>
              <td><?= htmlspecialchars($trabalho['nome_categoria'] ?? 'N/D') ?></td>
              <td><?= htmlspecialchars($trabalho['nome_area'] ?? 'N/D') ?></td>
              <td>
                <button
                  class="btn btn-success abrir-modal-avaliacao"
                  data-bs-toggle="modal"
                  data-bs-target="#avaliarModal"
                  data-id_trabalho="<?= $trabalho['id_trabalhos'] ?>"
                  data-titulo="<?= htmlspecialchars($trabalho['titulo']) ?>"
                  data-escola="<?= htmlspecialchars($trabalho['nome_escola']) ?>"
                  data-categoria="<?= htmlspecialchars($trabalho['nome_categoria']) ?>"
                  data-area="<?= htmlspecialchars($trabalho['nome_area'] ?? 'N/D') ?>">
                  Avaliar
                </button>

              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </main>

  <!-- Modal Avaliar -->
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
                <?php
                ?>
                <!-- 
                 modal de avaliação
                -->
                <strong class="text">Título: <span id="modalTitulo"><?= htmlspecialchars($trabalho['titulo']) ?></span></strong><br /><br />
                <strong class="text">Escola: <span id="modalEscola"><?= htmlspecialchars($trabalho['nome_escola'] ?? 'N/D') ?></span></strong>
              </p>
              <p style="float: right; margin-right: 10%;">
                <strong class="text">Categoria: <span id="modalCategoria"><?= htmlspecialchars($trabalho['nome_categoria'] ?? 'N/D') ?></span></strong><br /><br />
                <strong class="text">Área: <span id="modalArea"><?= htmlspecialchars($trabalho['nome_area'] ?? 'N/D') ?></span></strong>
              </p>
              <div style="clear: both;"></div>
            </div>

            <form id="formAvaliacao" action="../php/SalvarAvaliacao.php" method="post">
              <input type="hidden" name="id_trabalho" id="id_trabalho" value="" />
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
                    <td><input type="text" class="form-control nota-auto" name="criterio1" maxlength="5" required /></td>
                    <td><input type="text" class="form-control" name="comentario1" /></td>
                  </tr>

                  <tr>
                    <td><b>Relevância da pesquisa</b></td>
                    <td><input type="text" class="form-control nota-auto" name="criterio2" maxlength="5" required /></td>
                    <td><input type="text" class="form-control" name="comentario2" /></td>
                  </tr>
                  <tr>
                    <td><b>Conhecimento científico fundamentado e contextualização do problema abordado</b></td>
                    <td><input type="text" class="form-control nota-auto" name="criterio3" maxlength="5" required /></td>
                    <td><input type="text" class="form-control" name="comentario3" /></td>
                  </tr>
                  <tr>
                    <td><b>Impacto para a construção de uma sociedade que promova os saberes científicos em tempos de crise climática global</b></td>
                    <td><input type="text" class="form-control nota-auto" name="criterio4" maxlength="5" required /></td>
                    <td><input type="text" class="form-control" name="comentario4" /></td>
                  </tr>
                  <tr>
                    <td><b>Metodologia científica conectada com os objetivos, resultados e conclusões</b></td>
                    <td><input type="text" class="form-control nota-auto" name="criterio5" maxlength="5" required /></td>
                    <td><input type="text" class="form-control" name="comentario5" /></td>
                  </tr>
                  <tr>
                    <td><b>Clareza e objetividade na linguagem apresentada</b></td>
                    <td><input type="text" class="form-control nota-auto" name="criterio6" maxlength="5" required /></td>
                    <td><input type="text" class="form-control" name="comentario6" /></td>
                  </tr>
                  <tr>
                    <td><b>Banner</b></td>
                    <td><input type="text" class="form-control nota-auto" name="criterio7" maxlength="5" required /></td>
                    <td><input type="text" class="form-control" name="comentario7" /></td>
                  </tr>
                  <tr>
                    <td><b>Caderno de campo</b></td>
                    <td><input type="text" class="form-control nota-auto" name="criterio8" maxlength="5" required /></td>
                    <td><input type="text" class="form-control" name="comentario8" /></td>
                  </tr>
                  <tr>
                    <td><b>Processo participativo e solidário</b></td>
                    <td><input type="text" class="form-control nota-auto" name="criterio9" maxlength="5" required /></td>
                    <td><input type="text" class="form-control" name="comentario9" /></td>
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

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const inputsNotas = document.querySelectorAll('.nota-auto');

      inputsNotas.forEach(input => {
        input.addEventListener('input', () => {
          let valor = input.value.replace(/\D/g, '');

          if (valor.length > 4) {
            valor = valor.slice(0, 4);
          }

          valor = valor.padStart(3, '0');

          let intParte = valor.slice(0, -2);
          let decimalParte = valor.slice(-2);
          let resultado = `${parseInt(intParte, 10)},${decimalParte}`;

          let numero = parseFloat(resultado.replace(',', '.'));
          if (numero > 10) {
            resultado = '10,00';
          }

          input.value = resultado;
        });

        input.addEventListener('blur', () => {
          let numero = parseFloat(input.value.replace(',', '.'));

          if (isNaN(numero) || numero < 0 || numero > 10) {
            input.value = '0,00';
            input.classList.add('is-invalid');
          } else {
            input.value = numero.toFixed(2).replace('.', ',');
            input.classList.remove('is-invalid');
          }
        });
      });

      const form = document.getElementById('formAvaliacao');
      form.addEventListener('submit', (e) => {
        let valido = true;

        inputsNotas.forEach(input => {
          const valorNumerico = parseFloat(input.value.replace(',', '.'));
          if (isNaN(valorNumerico) || valorNumerico < 0 || valorNumerico > 10) {
            input.classList.add('is-invalid');
            valido = false;
          } else {
            input.classList.remove('is-invalid');
            input.value = valorNumerico.toFixed(2);
          }
        });

        if (!valido) {
          e.preventDefault();
          alert("Corrija as notas inválidas (valores entre 0,00 e 10,00)");
        }
      });
    });
    $('.abrir-modal-avaliacao').on('click', function() {
      $('#modalTitulo').text($(this).data('titulo'));
      $('#modalEscola').text($(this).data('escola'));
      $('#modalCategoria').text($(this).data('categoria'));
      $('#modalArea').text($(this).data('area'));

      const trabalhoId = $(this).data('id_trabalho');
      $('#id_trabalho').val(trabalhoId);
    });
  </script>
</body>

</html>