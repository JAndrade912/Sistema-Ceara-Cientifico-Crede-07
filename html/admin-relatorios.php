<?php
require_once '../php/Connect.php';

$escolas = $pdo->query("SELECT id_escolas, nome FROM Escolas ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
$categorias = $pdo->query("SELECT id_categoria, nome_categoria FROM Categorias ORDER BY nome_categoria")->fetchAll(PDO::FETCH_ASSOC);
$areas = $pdo->query("SELECT id_area, nome_area FROM Areas ORDER BY nome_area")->fetchAll(PDO::FETCH_ASSOC);

$trabalhos = $pdo->query("
  SELECT 
    t.id_trabalhos,
    t.titulo,
    e.nome AS escola,
    c.nome_categoria AS categoria,
    a.nome_area AS area
  FROM Trabalhos t
  LEFT JOIN Escolas e ON t.id_escolas = e.id_escolas
  LEFT JOIN Categorias c ON t.id_categoria = c.id_categoria
  LEFT JOIN Areas a ON t.id_areas = a.id_area
  ORDER BY t.titulo
")->fetchAll(PDO::FETCH_ASSOC);
?>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Relatórios</title>

  <link href="../boostrap/CSS/bootstrap.min.css" rel="stylesheet">
  <script src="../boostrap/JS/bootstrap.bundle.min.js"></script>
  <link href="../boostrap/CSS/bootstrap-icons.css" rel="stylesheet">
  <script src="../boostrap/JS/jquery.min.js"></script>
  <link rel="stylesheet" href="../assets/styles/relatorios.css">
</head>

<body>
  <div id="overlay" onclick="closeMobileSidebar()"></div>
  <button id="mobile-toggle" onclick="toggleSidebar()">
    <i><img src="../assets/img/menu.png"></i>
  </button>
  <div id="sidebar" style="background-color: #4C8F5A;">
    <div>
      <button class="toggle-btn" onclick="toggleSidebar()">
        <img src="../assets/img/SIMBOLO.png" alt="SACC">
        <span class="brand-text">SACC</span>
      </button>
      <ul class="nav flex-column">
        <li><a href="admin-dashboard.php"><i><img src="../assets/img/dashboard.png" class="dashboard"></i> <span
              class="label-text">Dashboard</span></a></li>
        <li><a href="admin-escolas.php"><i><img src="../assets/img/escola.png" class="escola"></i> <span
              class="label-text">Escolas</span></a></li>
        <li><a href="admin-trabalhos.php"><i><img src="../assets/img/trabalho.png" class="trabalho"></i> <span
              class="label-text">Trabalhos</span></a></li>
        <li><a href="admin-jurados.php"><i><img src="../assets/img/Jurados.png" class="jurado"></i> <span
              class="label-text">Jurados</span></a></li>
        <li><a href="admin-relatorios.php"><i><img src="../assets/img/relatorio.png" class="relatorio"></i> <span
              class="label-text">Relatórios</span></a></li>
      </ul>
    </div>
    <ul class="nav flex-column bottom-nav">
      <li><a href="../php/AdmLogout.php"><img src="../assets/img/sair.png" class="sair"> <span
            class="label-text">Sair</span></a></li>
    </ul>
  </div>
  <main id="main">
    <h2>Relatórios de Trabalhos</h2>
    <br>
    <hr><br>

    <div class="filter-group">
      <select id="Filtro_escola">
        <option value="">Selecione a Escola</option>
        <?php foreach ($escolas as $escola): ?>
          <option value="<?= htmlspecialchars($escola['nome']) ?>">
            <?= htmlspecialchars($escola['nome']) ?>
          </option>
        <?php endforeach; ?>
      </select>
      <select id="Filtro_categoria">
        <option value="">Selecione a Categoria</option>
        <?php foreach ($categorias as $categoria): ?>
          <option value="<?= htmlspecialchars($categoria['id_categoria']) ?>">
            <?= htmlspecialchars($categoria['nome_categoria']) ?>
          </option>
        <?php endforeach; ?>
      </select>
      <select id="Filtro_area">
        <option value="">Selecione a Área</option>
      </select>
    </div>
    <br>
    <div class="report-buttons">
      <button class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#modalJurado"
        style="background-color: #4C8F5A;">Por Jurado</button>
      <button class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#modalAmbosJurados"
        style="background-color: #4C8F5A;">Ambos os Jurados</button>
      <button class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#modalPorEscola"
        style="background-color: #4C8F5A;">Por Escola</button>
      <button class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#modalRanking"
        style="background-color: #4C8F5A;">Ranking</button>
    </div>
    <br>
    <!-- Tabela de trabalhos -->
    <table class="table table-striped table-bordered" id="workTable">
      <thead class=" table-success" style="border: 1px solid">
        <tr class="tr">
          <th class="th">Título do Trabalho</th>
          <th class="th">Escola</th>
          <th class="th">Categoria</th>
          <th class="th">Área</th>
          <th class="th">Download</th>
        </tr>
      </thead>
      <tbody class="table-striped text-center" id="workTbody" style="border: 1px solid">
        <?php foreach ($trabalhos as $t): ?>
          <?php
          // Consulta para buscar o id de um jurado que avaliou esse trabalho
          $stmt = $pdo->prepare("SELECT id_jurado FROM Avaliacoes WHERE id_trabalho = :id_trabalho LIMIT 1");
          $stmt->execute(['id_trabalho' => $t['id_trabalhos']]);
          $juradoRow = $stmt->fetch(PDO::FETCH_ASSOC);

          $id_jurado = $juradoRow['id_jurado'] ?? null;
          ?>
          <tr>
            <td><?= htmlspecialchars($t['titulo']) ?></td>
            <td><?= htmlspecialchars($t['escola'] ?? '—') ?></td>
            <td><?= htmlspecialchars($t['categoria'] ?? '—') ?></td>
            <td><?= htmlspecialchars($t['area'] ?? '—') ?></td>
            <td>
              <?php if ($id_jurado): ?>
                <button class="btn bg-danger me-1" data-bs-toggle="modal" data-bs-target="#modalPdf">PDF</button>
                <button class="btn btn-success me-1" data-bs-toggle="modal" data-bs-target="#modalExcel">Excel</button>
              <?php else: ?>
                <span class="text-muted">Sem avaliação</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <div class="modal fade" id="modalPdf" tabindex="-1" aria-labelledby="modalPdfLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalPdfLabel">Selecione o Jurado</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
          </div>
          <div class="modal-body">
            <div class="d-flex justify-content-center" style="gap: 20px;">
              <a href="#" class="btn btn-primary">Jurado 1</a>
              <a href="#" class="btn btn-primary">Jurado 2</a>
            </div>

          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modalExcel" tabindex="-1" aria-labelledby="modalExcelLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalExcelLabel">Selecione o Jurado</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
          </div>
          <div class="modal-body">
            <div class="d-flex justify-content-center" style="gap: 20px;">
              <a href="#" class="btn btn-primary">Jurado 1</a>
              <a href="#" class="btn btn-primary">Jurado 2</a>
            </div>

          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modalJurado" tabindex="-1" aria-labelledby="modalJuradoLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalJuradoLabel">Relatório Por Jurado</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
          </div>
          <div class="modal-body">
            <form method="POST" action="#" id="idSelJurado">
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
              <!-- Área para PcD -->
              <div id="jurado-area2" style="display:none;">
                <label for="id_area">Área</label>
                <select class="form-control" name="area">
                  <option selected disabled>Selecione...</option>
                  <option value="6">Ensino Fundamental</option>
                  <option value="7">Ensino Médio</option>
                </select>
              </div>
              <?php
              $stmt = $pdo->query("SELECT id_jurados,nome FROM Jurados ORDER BY nome ASC");
              $jurados = $stmt->fetchAll(PDO::FETCH_ASSOC);
              ?>
              <div id="jurado-nome" style="display:none;">
                <label for="jurado-nome" class="form-label">Nome do Jurado</label>
                <select id="jurado-nome" class="form-control" name="nome" required>
                  <option selected disabled>Selecione o Jurado</option>
                  <?php foreach ($jurados as $jurado): ?>
                    <option value="<?= htmlspecialchars($jurado['id_jurados']) ?>">
                      <?= htmlspecialchars($jurado['nome']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
              style="margin-top: 17px;">Fechar</button>
            <button type="button" class="btn btn-success mt-3">Gerar Relatório</button>
          </div>
        </div>
      </div>
    </div>



    <div class="modal fade" id="modalAmbosJurados" tabindex="-1" aria-labelledby="modalAmbosJuradosLabel"
      aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalAmbosJuradosLabel">Relatório por Ambos os Jurados</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
          </div>
          <div class="modal-body">
            <p>Gerar relatório dos trabalhos avaliados por ambos os jurados.</p>

            <label for="categoria-Ambos" class="form-label">Categoria</label>
            <select id="categoria-Ambos" class="form-select mb-2" name="categoria">
              <option value="">Selecione a Categoria</option>
              <option value="1">Ensino Médio</option>
              <option value="2">Ensino Médio - Ações Afirmativas e CEJAs EM</option>
              <option value="3">Pesquisa Júnior</option>
              <option value="4">PcD</option>
            </select>
            <div id="area-Ambos" style="display: none;">
              <label for="area-Ambos" class="form-label">Área</label>
              <select class="form-select">
                <option value="">Selecione a Área</option>
                <option value="1">Linguagens, Códigos e suas Tecnologias - LC</option>
                <option value="2">Matemática e suas Tecnologias - MT</option>
                <option value="3">Ciências da Natureza, Educação Ambiental e Engenharias - CN</option>
                <option value="4">Ciências Humanas e Sociais Aplicadas - CH</option>
                <option value="5">Robótica, Automação e Aplicação das TIC</option>
              </select>
            </div>
            <div id="area-Ambos2" style="display: none;">
              <label for="area-Ambos2" class="form-label">Área</label>
              <select class="form-select">
                <option value="">Selecione a Área</option>
                <option value="1">Ensino Fundamental</option>
                <option value="2">Ensino Médio</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
              style="margin-top: 17px;">Fechar</button>
            <button type="button" class="btn btn-success mt-3">Gerar Relatório</button>
          </div>
        </div>
      </div>
    </div>


    <!-- Modal de relatorios por escola -->
    <!-- Pendente: vincular para a geração de PDF de acordo com o ID fornecido  -->

    <div class="modal fade" id="modalPorEscola" tabindex="-1" aria-labelledby="modalPorEscolaLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalPorEscolaLabel">Relatório por Escola</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
          </div>
          <div class="modal-body">
            <p>Gerar relatório dos trabalhos submetidos por uma escola específica.</p>

            <label for="escola" class="form-label">Escola</label>
            <select id="selectEscola" name="escola" class="form-control" required>
              <option value="">Selecione a Escola</option>
              <?php foreach ($escolas as $escola): ?>
                <option value="<?= htmlspecialchars($escola['id_escolas']) ?>">
                  <?= htmlspecialchars($escola['nome']) ?>
                </option>
              <?php endforeach; ?>
            </select>

            <button type="button" class="btn btn-success mt-3" onclick="gerarRelatorio()">Gerar Relatório</button>

            <script>
              function gerarRelatorio() {
                const idEscola = document.getElementById('selectEscola').value;
                if (!idEscola) {
                  alert('Selecione uma escola antes de gerar o relatório.');
                  return;
                }
                window.open(`../html/relat_escola.php?id_escola=${idEscola}&type=pdf`, '_blank');
              }
            </script>

          </div>
        </div>
      </div>
    </div>


    <!-- Modal Ranking - CORRIGIDO E ADICIONADO SELECTS -->
    <div class="modal fade" id="modalRanking" tabindex="-1" aria-labelledby="modalRankingLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalRankingLabel">Ranking de Trabalhos</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
          </div>
          <div class="modal-body">
            <p>Gerar relatório de classificação dos trabalhos.</p>

            <label for="categoria-Ranking" class="form-label">Categoria</label>
            <select id="categoria-Ranking" class="form-select mb-2">
              <option value="">Selecione a Categoria</option>
              <option value="1">Ensino Médio</option>
              <option value="2">Ensino Médio - Ações Afirmativas e CEJAs EM</option>
              <option value="3">Pesquisa Júnior</option>
              <option value="4">PcD</option>
            </select>

            <div id="area-Ranking" style="display: none;">
              <label for="area-Ranking" class="form-label">Área</label>
              <select class="form-select">
                <option value="">Selecione a Área</option>
                <option value="1">Linguagens, Códigos e suas Tecnologias - LC</option>
                <option value="2">Matemática e suas Tecnologias - MT</option>
                <option value="3">Ciências da Natureza, Educação Ambiental e Engenharias - CN</option>
                <option value="4">Ciências Humanas e Sociais Aplicadas - CH</option>
                <option value="5">Robótica, Automação e Aplicação das TIC</option>
              </select>
            </div>
            <div id="area-Ranking2" style="display: none;">
              <label for="area-Ranking2" class="form-label">Área</label>
              <select class="form-select">
                <option value="">Selecione a Área</option>
                <option value="1">Ensino Fundamental</option>
                <option value="2">Ensino Médio</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
              style="margin-top: 17px;">Fechar</button>
            <button type="button" class="btn btn-success mt-3">Gerar Relatório</button>
          </div>
        </div>
      </div>
    </div>

  </main>
  <script src="../bootstrap/JS/jquery.min.js"></script>
  <script>
    $('#categoria-Ambos').change(function () {
      var categoria = $(this).val();
      if (categoria === '1' || categoria === '2') {
        $('#area-Ambos').slideDown();
        $('#area-Ambos2').slideUp();
      } else if (categoria === '4') {
        $('#area-Ambos').slideUp();
        $('#area-Ambos2').slideDown();
      } else if (categoria === '3') {
        $('#area-Ambos, #area-Ambos2').slideUp();
      }
    });

    $('#categoria-Ranking').change(function () {
      var categoria = $(this).val();
      if (categoria === '1' || categoria === '2') {
        $('#area-Ranking').slideDown();
        $('#area-Ranking2').slideUp();
      } else if (categoria === '4') {
        $('#area-Ranking').slideUp();
        $('#area-Ranking2').slideDown();
      } else if (categoria === '3') {
        $('#area-Ranking, #area-Ranking2').slideUp();
      }
    });

    $('#jurado-categoria').change(function () {
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

    $('#jurado-categoria').change(function () {
      var categoria = $(this).val();
      if (categoria === '1' || categoria === '2') {
        $('#jurado-area').slideDown();
        $('#jurado-area2').slideUp();
      } else if (categoria === '4') {
        $('#jurado-area2').slideDown();
        $('#jurado-area').slideUp();
      } else if (categoria === '3') {
        $('#jurado-nome').slideDown();
        $('#jurado-area, #jurado-area2').slideUp();
      }
    });
    $('#jurado-area, #jurado-area2').change(function () {
      $('#jurado-nome').slideDown();
    });

    // SIDEBAR
    function toggleSidebar() {
      if (window.innerWidth <= 768) {
        $('#sidebar').toggleClass('mobile-open');
        $('#overlay').toggleClass('show');
      } else {
        $('#sidebar').toggleClass('collapsed');
        $('#main').toggleClass('collapsed');
      }
    }

    function closeMobileSidebar() {
      $('#sidebar').removeClass('mobile-open');
      $('#overlay').removeClass('show');
    }
    $(window).on('resize', function () {
      if (window.innerWidth > 768) {
        $('#sidebar').removeClass('mobile-open');
        $('#overlay').removeClass('show');
      }
    });

    const areas = {
      "1": ["Linguagens, Códigos e suas Tecnologias - LC", "Matemática e suas Tecnologias - MT", "Ciências da Natureza - CN", "Educação Ambiental e Engenharias - CH", "Robótica, Automação e Aplicação das TIC"],
      "2": ["Linguagens, Códigos e suas Tecnologias - LC", "Matemática e suas Tecnologias - MT", "Ciências da Natureza - CN", "Educação Ambiental e Engenharias - CH", "Robótica, Automação e Aplicação das TIC"],
      "3": [],
      "4": ["Ensino Fundamental", "Ensino Médio"]
    };

    $('#Filtro_categoria').on('change', function () {
      const cat = $(this).val();
      const areaSelect = $('#Filtro_area');
      areaSelect.html('<option value="">Selecione a Área</option>');
      if (areas[cat]) {
        areas[cat].forEach(a => {
          areaSelect.append(`<option value="${a}">${a}</option>`);
        });
      }
      filterWorks();
    });

    $('#Filtro_escola, #Filtro_area').on('change', filterWorks);


    function filterWorks() {
      const escola = $('#Filtro_escola').val();
      const categoriaVal = $('#Filtro_categoria').val();
      const categoriaName = $('#Filtro_categoria option:selected').text();
      const area = $('#Filtro_area').val();


      const filtered = works.filter(w => {
        return (!escola || id_escola === escola) &&
          (!categoria || id_categoria === categoria) &&
          (!area || id_area === area);
      });
    }


    filterWorks();
  </script>
</body>

</html>