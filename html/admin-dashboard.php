<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../php/Connect.php';
if (!isset($_SESSION['id_admin']) || !isset($_SESSION['usuario'])) {
  header('Location: ../html/login_adm.php');
  exit();
}

$sql = "SELECT 
    t.id_trabalhos,
    t.titulo,
    e.nome AS escola,
    c.nome_categoria,
    a.nome_area
FROM Trabalhos t
LEFT JOIN Escolas e ON t.id_escolas = e.id_escolas
LEFT JOIN Jurados j ON t.id_jurados = j.id_jurados
LEFT JOIN Categorias c ON t.id_categoria = c.id_categoria
LEFT JOIN Areas a ON t.id_areas = a.id_area
ORDER BY t.id_trabalhos DESC";

$result = $pdo->query($sql);
$row = $result->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->query("SELECT id_escolas,nome FROM Escolas ORDER BY nome ASC");
$escolas = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->query("SELECT id_categoria,nome_categoria FROM Categorias ORDER BY nome_categoria ASC");
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->query("SELECT id_area,nome_area FROM Areas ORDER BY nome_area ASC");
$areas = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->query("SELECT id_jurados,nome FROM Jurados ORDER BY nome ASC");
$jurados = $stmt->fetchAll(PDO::FETCH_ASSOC);

// area destinada as consultas de estado dos cards em tempo real do dashboard do admin
$stmt = $pdo->query("SELECT COUNT(*) AS total_escolas FROM Escolas");
$total_escolas = $stmt->fetch(PDO::FETCH_ASSOC)['total_escolas'];

$stmt = $pdo->query("SELECT COUNT(*) AS total_trabalhos FROM Trabalhos");
$total_trabalhos = $stmt->fetch(PDO::FETCH_ASSOC)['total_trabalhos'];

$stmt = $pdo->query("SELECT COUNT(*) AS total_jurados FROM Jurados");
$total_jurados = $stmt->fetch(PDO::FETCH_ASSOC)['total_jurados'];


?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard Administrativo</title>

  <link href="../boostrap/CSS/bootstrap.min.css" rel="stylesheet">
  <script src="../boostrap/JS/bootstrap.bundle.min.js"></script>
  <link href="../boostrap/CSS/bootstrap-icons.css" rel="stylesheet">
  <script src="../boostrap/JS/jquery.min.js"></script>
  <link rel="stylesheet" href="../assets/styles/dashboard-admin.css">

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
        <li><a href="admin-relatorios.html"><i><img src="../assets/img/relatorio.png" class="relatorio"></i> <span
              class="label-text">Relatórios</span></a></li>
      </ul>
    </div>
    <ul class="nav flex-column bottom-nav">
      <li><a href="../php/AdmLogout.php"><img src="../assets/img/sair.png" class="sair"> <span
            class="label-text">Sair</span></a></li>
    </ul>
  </div>
  <main id="main">
    <div class="container-fluid">
      <h2>Dashboard Administrativo</h2>
      <p class="text-muted">Escolha uma das opções abaixo para realizar cadastros:</p>
      <div class="row text-center mb-4">
        <div class="col">
          <button class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#modalInstituicao"
            style="background-color: #4C8F5A;">Cadastrar Instituição</button>
          <button class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#modalJurado"
            style="background-color: #4C8F5A;">Cadastrar Jurado</button>
          <button class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#modalTrabalho"
            style="background-color: #4C8F5A;">Cadastrar Trabalho</button>
          <button class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#modalAssociacao"
            style="background-color: #4C8F5A;">Associar Jurado</button>
        </div>
      </div>
      <div class="modal fade" id="modalInstituicao" tabindex="-1" aria-labelledby="modalInstituicaoLabel"
        aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="modalInstituicaoLabel">Cadastrar Instituição</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
              <form method="POST" action="../php/Cadescola.php" id="idCadEscola">
                <label for="instituicao-nome" class="form-label">Nome da Instituição</label>
                <input type="text" id="instituicao-nome" class="form-control" name="nome"
                  placeholder="Digite o nome da instituição" required>

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
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" id="modalJurado" tabindex="-1" aria-labelledby="modalJuradoLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="modalJuradoLabel">Cadastrar Jurado</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
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
                <select id="jurado-categoria" class="form-control" name="categoria[]" required>
                  <option selected disabled>Selecione...</option>
                  <option value="1">I - Ensino Médio</option>
                  <option value="2">II - Ensino Médio - Ações Afirmativas e CEJAs EM</option>
                  <option value="3">III - Pesquisa Júnior</option>
                  <option value="4">IV - PcD</option>
                </select>
                <div id="jurado-area" style="display:none;">
                  <label for="id_areas">Área</label>
                  <select class="form-control" name="area[]">
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
                  <select class="form-control" name="area[]">
                    <option selected disabled>Selecione...</option>
                    <option value="6">Ensino Fundamental</option>
                    <option value="7">Ensino Médio</option>
                  </select>
                </div>
                <!-- Segunda categoria -->
                <div class="mb-3" style="display: none;">
                  <label for="categoria_sec" class="form-label">Categoria</label>
                  <select name="categoria[]" id="Adicionar-sec-categoria" class="form-control">
                    <option selected disabled>Selecione...</option>
                    <option value="1">I - Ensino Médio</option>
                    <option value="2">II - Ensino Médio - Ações Afirmativas e CEJAs EM</option>
                    <option value="3">III - Pesquisa Júnior</option>
                    <option value="4">IV - PcD</option>
                  </select>
                </div>
                <!-- Segunda área para categoria -->
                <div class="mb-3" id="areajuradosec" style="display: none;">
                  <label for="area_sec" class="form-label">Área</label>
                  <select name="area[]" id="area1" class="form-control">
                    <option selected disabled>Selecione...</option>
                    <option value="1">Linguagens, Códigos e suas Tecnologias - LC</option>
                    <option value="2">Matemática e suas Tecnologias - MT</option>
                    <option value="3">Ciências da Natureza, Educação Ambiental e Engenharias - CN</option>
                    <option value="4">Ciências Humanas e Sociais Aplicadas - CH</option>
                    <option value="5">Robótica, Automação e Aplicação das TIC</option>
                  </select>
                </div>
                <!-- Segunda área para PcD -->
                <div class="mb-3" id="area2juradosec" style="display: none;">
                  <label for="area_sec2" class="form-label">Área</label>
                  <select name="area[]" id="area2" class="form-control">
                    <option selected disabled>Selecione...</option>
                    <option value="6">Ensino Fundamental</option>
                    <option value="7">Ensino Médio</option>
                  </select>
                </div>
                <input type="submit" value="Enviar" class="btn btn-success" style="margin-top:10px; float: left;">
                <button type="button" class="btn btn-success" id="adicionar-categoria-btn"
                  style="background-color: #2071b4ff; float:right; margin-top:10px;">Adicionar Categoria</button>
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
                  <select name="area" class="form-control">
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

      <div class="modal fade" id="modalAssociacao" tabindex="-1" aria-labelledby="modalAssociacaoLabel"
        aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="modalAssociacaoLabel">Associar Jurado a Trabalho</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
              <form action="../php/Associartrabalho.php" method="POST" id="idCadAssociacao">
                <div class="mb-3">
                  <label for="categoria" class="form-label">Categoria</label>
                  <select name="categoria" id="associar-categoria" class="form-control" required>
                    <option selected disabled>Selecione...</option>
                    <?php foreach ($categorias as $categoria): ?>
                      <option value="<?= htmlspecialchars($categoria['id_categoria']) ?>">
                        <?= htmlspecialchars($categoria['nome_categoria']) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="mb-3" id="areajurado" style="display:none;">
                  <label for="area" class="form-label">Área</label>
                  <select name="area" id="area1" class="form-control" required>
                    <option selected disabled>Selecione...</option>
                    <?php foreach ($areas as $area): ?>
                      <option value="<?= htmlspecialchars($area['id_area']) ?>">
                        <?= htmlspecialchars($area['nome_area']) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="mb-3" id="area2jurado" style="display:none;">
                  <label for="area" class="form-label">Área</label>
                  <select name="area" id="area2" class="form-control" required>
                    <option selected disabled>Selecione...</option>
                    <option value="6">Ensino Fundamental</option>
                    <option value="7">Ensino Médio</option>
                  </select>
                </div>

                <div class="mb-3" id="atribuir-jurado" style="display: none;">
                  <label for="jurado" class="form-label">Jurado</label>
                  <select name="jurado" id="jurado" class="form-control" required>
                    <option selected disabled>Selecione o Jurado</option>
                    <?php foreach ($jurados as $jurado): ?>
                      <option value="<?= htmlspecialchars($jurado['id_jurados']) ?>">
                        <?= htmlspecialchars($jurado['nome']) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <div class="mb-3" id="trabalhojurado" style="display:none;">
                  <label for="trabalho" class="form-label">Trabalhos</label>
                  <table class="table table-hover">
                    <tbody id="trabalho-tbody">
                      <?php
                      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo '<tr>';
                        echo '<td>' .'<input type="checkbox" name="trabalhos[]" value="'. $row['id_trabalhos'] .'">'. '</td>';
                        echo '<td>' . $row['nome_area'] . '</td>';
                        echo '</tr>';
                      }
                      ?>
                    </tbody>
                  </table>
                  <button type="button" class="btn btn-sm btn-primary mb-2" id="selecionar-todos">Selecionar
                    Todos</button>
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
      <div class="row stat-row">
        <div class="col-sm-4">
          <div class="stat-box stat-primary">
            <i><img src="../assets/img/escola.png" class="escola" style="width: 25px;"></i>
            <h2 id="total-escolas"><?php echo $total_escolas; ?></h2>
            <p>Escolas Cadastradas</p>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="stat-box stat-primary">
            <i><img src="../assets/img/trabalho.png" class="trabalho" style="width: 25px;"></i>
            <h2 id="total-trabalhos"><?php echo $total_trabalhos; ?></h2>
            <p>Trabalhos Cadastrados</p>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="stat-box stat-primary">
            <i><img src="../assets/img/Jurados.png" class="jurado" style="width: 25px;"></i>
            <h2 id="total-jurados"><?php echo $total_jurados; ?></h2>
            <p>Jurados Cadastrados</p>
          </div>
        </div>
      </div>

      <!-- RANKING PRELIMINAR ÁREA -->
      <div class="row">
        <div class="col-12">
          <div class="ranking-box mt-4">
            <h3 class="text-center">Ranking Preliminar</h3>
            <form action="" method="POST">
              <div class="row mt-3 flex">
                <div class="col-sm-6">
                  <label>Categoria</label>
                  <select id="ranking-categoria" class="form-control" name="categoria">
                    <option value="">-- Selecione a Categoria --</option>
                    <option value="1" <?= $categoria == "1" ? "selected" : "" ?>>I - Ensino Médio</option>
                    <option value="2" <?= $categoria == "2" ? "selected" : "" ?>>II - Ensino Médio - Ações Afirmativas e CEJAs EM</option>
                    <option value="3" <?= $categoria == "3" ? "selected" : "" ?>>III - Pesquisa Júnior</option>
                    <option value="4" <?= $categoria == "4" ? "selected" : "" ?>>IV - PcD</option>
                  </select>
                </div>
                <div class="col-sm-6">
                  <label>Área</label>
                  <select id="ranking-area" class="form-control">
                    <option value="">-- Selecione a Área --</option>
                    <option value="1" <?= $area == "1" ? "selected" : "" ?>>Linguagens, Códigos e suas Tecnologias - LC</option>
                    <option value="2" <?= $area == "2" ? "selected" : "" ?>>Matemática e suas Tecnologias - MT</option>
                    <option value="3" <?= $area == "3" ? "selected" : "" ?>>Ciências da Natureza, Educação Ambiental e Engenharias - CN</option>
                    <option value="4" <?= $area == "4" ? "selected" : "" ?>>Ciências Humanas e Sociais Aplicadas - CH</option>
                    <option value="5" <?= $area == "5" ? "selected" : "" ?>>Robótica, Automação e Aplicação das TIC</option>
                    <option value="6" <?= $area == "6" ? "selected" : "" ?>>Ensino Fundamental</option>
                    <option value="7" <?= $area == "7" ? "selected" : "" ?>>Ensino Médio</option>
                  </select>
                </div>
              </div>
              <div class="mt-3 text-right">
                <input type="submit" value="Filtrar" class="btn btn-success">
              </div>
            </form>
            <?php
            require_once '../php/Connect.php';
            $categoria = $_POST['categoria'] ?? null;
            $area = $_POST['area'] ?? null;

            $sql = "SELECT t.titulo, e.nome AS escola, c.nome_categoria, a.nome_area 
                FROM Trabalhos t
                  LEFT JOIN Escolas e ON t.id_escolas = e.id_escolas
                  LEFT JOIN Categorias c ON t.id_categoria = c.id_categoria
                  LEFT JOIN Areas a ON t.id_areas = a.id_area
                  WHERE 1=1";

            $params = [];

            if (!empty($categoria)) {
              $sql .= " AND t.id_categoria = :categoria";
              $params[':categoria'] = $categoria;
            }
            if (!empty($area)) {
              $sql .= " AND t.id_areas = :area";
              $params[':area'] = $area;
            }

            $sql .= " ORDER BY t.id_trabalhos DESC";

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);

            ?>
            <!-- TABELA DO RANKING PRELIMINAR QUE EXIBE OS TRABALHOS POR FILTRO -->
            <div class="table-responsive mt-3">
              <table class="table table-hover">
                <thead class="text-center">
                  <tr>
                    <th>Título</th>
                    <th>Escola</th>
                    <th>Categoria</th>
                    <th>Área</th>
                    <th>Jurado 1</th>
                    <th>Jurado 2</th>
                    <th>Nota Final</th>
                  </tr>
                </thead>
                <tbody id="ranking-tbody" class="text-center">
                  <?php
                  if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                      echo '<tr>';
                      echo '<td>' . htmlspecialchars($row['titulo'] ?? '') . '</td>';
                      echo '<td>' . htmlspecialchars($row['escola'] ?? '') . '</td>';
                      echo '<td>' . htmlspecialchars($row['nome_categoria']) . '</td>';
                      echo '<td>' . htmlspecialchars($row['nome_area'] ?? '') . '</td>';
                      echo '<td>-</td>';
                      echo '<td>-</td>';
                      echo '<td>-</td>';
                      echo '</tr>';
                    }
                  } else {
                    echo '<tr><td colspan="7" class="text-center">Nenhum resultado encontrado</td></tr>';
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
  <script>
    // Sidebar
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
    $(window).on('resize', function() {
      if (window.innerWidth > 768) {
        $('#sidebar').removeClass('mobile-open');
        $('#overlay').removeClass('show');
      }
    });
    $('#instituicao-tipo').change(function() {
      ($(this).val() === '1') ? $('#campo-ide').slideDown(): $('#campo-ide').slideUp();
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
    $('#adicionar-categoria-btn').click(function(event) {
      event.preventDefault();
      var container = $('#Adicionar-sec-categoria').parent();
      if (container.is(':visible')) {
        container.slideUp();
        $('#Adicionar-sec-categoria').val('');
        $('#areajuradosec, #area2juradosec').slideUp();
      } else {
        container.slideDown();
      }
    });
    $('#Adicionar-sec-categoria').change(function() {
      var categoria = $(this).val();
      if (categoria === '1' || categoria === '2') {
        $('#areajuradosec').slideDown();
        $('#area2juradosec').slideUp();
      } else if (categoria === '4') {
        $('#area2juradosec').slideDown();
        $('#areajuradosec').slideUp();
      } else {
        $('#areajuradosec, #area2juradosec').slideUp();
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
        $('#atribuir-jurado').slideDown();
        $('#areajurado, #area2jurado').slideUp();
      }
    });
    $('#area1, #area2').change(function() {
      $('#atribuir-jurado').slideDown();
    });
    $('#atribuir-jurado').change(function() {
      $('#trabalhojurado').slideDown();
    });

    $('#selecionar-todos').click(function() {
      const checkboxes = $('#trabalho-tbody input[type="checkbox"]');
      const todosSelecionados = checkboxes.length === checkboxes.filter(':checked').length;

      if (todosSelecionados) {
        checkboxes.prop('checked', false);
        $(this).text('Selecionar Todos');
      } else {
        checkboxes.prop('checked', true);
        $(this).text('Desmarcar Todos');
      }
    });
  </script>
</body>

</html>