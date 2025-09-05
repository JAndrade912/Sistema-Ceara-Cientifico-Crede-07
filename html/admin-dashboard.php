<?php
session_start();

if(!isset($_SESSION['id_admin']) || !isset($_SESSION['usuario'])){
  header('Location: ../html/login_adm.php');
  exit();
}
$userName = $_SESSION['usuario']; 
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
  <i class="bi bi-list"></i>
</button>

<div id="sidebar" style="background-color: #4C8F5A;">
  <div>
    <button class="toggle-btn" onclick="toggleSidebar()">
      <img src="../assets/img/SIMBOLO.png" alt="SACC">
      <span class="brand-text">SACC</span>
    </button>
    <ul class="nav flex-column">
      <li><a href="admin-dashboard.php"><i><img src="../assets/img/dashboard.png" class="dashboard"></i> <span class="label-text">Dashboard</span></a></li>
     <li><a href="admin-escolas.html"><i><img src="../assets/img/escola.png" class="escola"></i> <span class="label-text">Escolas</span></a></li>
     <li><a href="admin-trabalhos.html"><i><img src="../assets/img/trabalho.png" class="trabalho"></i> <span class="label-text">Trabalhos</span></a></li>
     <li><a href="admin-jurados.html"><i><img src="../assets/img/Jurados.png" class="jurado"></i> <span class="label-text">Jurados</span></a></li>
     <li><a href="admin-relatorios.html"><i><img src="../assets/img/relatorio.png" class="relatorio"></i> <span class="label-text">Relatórios</span></a></li>
    </ul>
  </div>
  <ul class="nav flex-column bottom-nav">
    <li><a href="../php/admLogout.php"><img src="../assets/img/sair.png" class="sair"> <span class="label-text">Sair</span></a></li>
  </ul>
</div>

<main id="main">
  <div class="container-fluid">
    <h2>Dashboard Administrativo</h2>
    <p class="text-muted">Escolha uma das opções abaixo para realizar cadastros:</p>


    <div class="row text-center mb-4">
      <div class="col">
        <button class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#modalInstituicao" style="background-color: #4C8F5A;">Cadastrar Instituição</button>
        <button class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#modalJurado" style="background-color: #4C8F5A;">Cadastrar Jurado</button>
        <button class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#modalTrabalho" style="background-color: #4C8F5A;">Cadastrar Trabalho</button>
      </div>
    </div>

    
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

<!--      MODAL DO JURADO  INICIO  -->
<div class="modal fade" id="modalJurado" tabindex="-1" aria-labelledby="modalJuradoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalJuradoLabel">Cadastrar Jurado</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
         <form action="/php/Cadjurado.php" method="POST" id="idCadJurado">
              <label for="nome">Nome</label>
              <input type="text" class="form-control" name="nome" placeholder="Digite seu nome" required>
              <label for="usuario">Apelido</label>
              <input type="text" class="form-control" name="usuario" placeholder="Digite seu nome de usuario" required>
              <label for="telefone">Telefone</label>
              <input type="text" class="form-control" name="telefone" placeholder="Digite seu telefone" required>
              <label for="cpf">CPF</label>
              <input type="text" class="form-control" name="cpf" placeholder="Digite seu CPF" required>
              <label for="email">E-mail SIC-CED</label>
              <input type="text" class="form-control" name="email" placeholder="Digite seu e-mail" required>
              <!--  ANALISAR A QUESTÃO DAS CATEGORIAS NO BD (ESTUDAR) -->
              <label for="id_categoria">Categoria</label>
              <select id="jurado-categoria" class="form-control" name="" required>
                <option selected disabled>Selecione...</option>
                <option value="1">I - Ensino Médio</option>
                <option value="2">II - Ensino Médio - Ações Afirmativas e CEJAs EM</option>
                <option value="3">III - Pesquisa Júnior</option>
                <option value="4">IV - PcD</option>
              </select>
              <!--  ANALISAR A QUESTÃO DAS CATEGORIAS NO BD (ESTUDAR) -->
              <div id="jurado-area" style="display:none;">

              <!--  ANALISAR A QUESTÃO DAS AREAS NO BD (ESTUDAR) -->
                  <label for="id_areas">Área</label>
                  <select class="form-control">
                    <option selected disabled>Selecione...</option>
                    <option value="1">Linguagens, Códigos e suas Tecnologias - LC</option>
                    <option value="2">Matemática e suas Tecnologias - MT</option>
                    <option value="3">Ciências da Natureza, Educação Ambiental e Engenharias - CN</option>
                    <option value="4">Ciências Humanas e Sociais Aplicadas - CH</option>
                    <option value="5">Robótica, Automação e Aplicação das TIC</option>
                  </select>
                </div>
              <!--  ANALISAR A QUESTÃO DAS AREAS NO BD (ESTUDAR) -->

              <!--  AREA ACESSADA PELA CATEGORIA PcD  -->
                <div id="jurado-area2" style="display:none;">
                  <label>Área</label>
                  <select class="form-control">
                    <option selected disabled>Selecione...</option>
                    <option value="1">Ensino Fundamental</option>
                    <option value="2">Ensino Médio</option>
                  </select>
                </div>
              <!--  AREA ACESSADA PELA CATEGORIA PcD  -->

              <input type="submit" value="Enviar" class="btn btn-success" style="margin-top:10px;">
            </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>
<!--     MODAL DO JURADO  FIM  -->

<div class="modal fade" id="modalTrabalho" tabindex="-1" aria-labelledby="modalTrabalhoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalJuradoLabel">Cadastrar Jurado</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <form>
              <label>Título do Trabalho</label>
              <input type="text" class="form-control" placeholder="Digite o nome do Trabalho" required>
              <label>Escola</label>
              <input type="text" class="form-control" placeholder="Digite o nome da Escola" required>
              <label for="trabalho-categoria">Categoria</label>
              <select id="trabalho-categoria" class="form-control" required>
                <option selected disabled>Selecione...</option>
                <option value="1">I - Ensino Médio</option>
                <option value="2">II - Ensino Médio - Ações Afirmativas e CEJAs EM</option>
                <option value="3">III - Pesquisa Júnior</option>
                <option value="4">IV - PcD</option>
              </select>

              <div id="trabalho-area" style="display:none;">
                <label>Área</label>
                <select class="form-control">
                  <option selected disabled>Selecione...</option>
                  <option value="1">Linguagens, Códigos e suas Tecnologias - LC</option>
                  <option value="2">Matemática e suas Tecnologias - MT</option>
                  <option value="3">Ciências da Natureza, Educação Ambiental e Engenharias - CN</option>
                  <option value="4">Ciências Humanas e Sociais Aplicadas - CH</option>
                  <option value="5">Robótica, Automação e Aplicação das TIC</option>
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
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>
    <div class="row stat-row">
      <div class="col-sm-4">
        <div class="stat-box stat-primary">
          <i><img src="../assets/img/escola.png" class="escola"></i>
          <h2 id="total-escolas">120</h2>
          <p>Escolas Cadastradas</p>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="stat-box stat-primary">
          <i><img src="../assets/img/trabalho.png" class="trabalho"></i>
          <h2 id="total-trabalhos">350</h2>
          <p>Trabalhos Cadastrados</p>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="stat-box stat-primary">
          <i><img src="../assets/img/Jurados.png" class="jurado"></i>
          <h2 id="total-jurados">50</h2>
          <p>Jurados Cadastrados</p>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <div class="ranking-box mt-4">
          <h3 class="text-center">Ranking Preliminar</h3>
          <div class="row mt-3">
            <div class="col-sm-6">
              <label>Categoria</label>
              <select id="ranking-categoria" class="form-control">
                <option selected disabled>-- Selecione a Categoria --</option>
                <option value="I">I - Ensino Médio</option>
                <option value="II">II - Ensino Médio - Ações Afirmativas e CEJAs EM</option>
                <option value="III">III - Pesquisa Júnior</option>
                <option value="IV">IV - PcD</option>
              </select>
            </div>
            <div class="col-sm-6">
              <label>Área</label>
              <select id="ranking-area" class="form-control" disabled>
                <option selected>-- Primeiro selecione a categoria --</option>
              </select>
            </div>
          </div>

          <div class="table-responsive mt-3">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>Classificação</th>
                  <th>Trabalho</th>
                  <th>Escola</th>
                  <th>-</th>
                  <th>-</th>
                  <th>Nota Final</th>
                </tr>
              </thead>
              <tbody id="ranking-tbody">
                <tr>
                  <td colspan="4" class="text-center text-muted">Selecione Categoria e Área para visualizar o ranking</td>
                </tr>
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
  $(window).on('resize', function () {
    if (window.innerWidth > 768) {
      $('#sidebar').removeClass('mobile-open');
      $('#overlay').removeClass('show');
    }
  });

  // Lógica dos modais e exibição de áreas
  $('#instituicao-tipo').change(function () {
    ($(this).val() === '1') ? $('#campo-ide').slideDown() : $('#campo-ide').slideUp();
  });
  $('#jurado-categoria').change(function () {
  var categoria = $(this).val();
  if (categoria === '1' || categoria === '2') {
    $('#jurado-area').slideDown(); $('#jurado-area2').slideUp();
  } else if (categoria === '4') {
    $('#jurado-area').slideUp(); $('#jurado-area2').slideDown();
  } else { $('#jurado-area, #jurado-area2').slideUp(); }
});
$('#trabalho-categoria').change(function () {
  var categoria = $(this).val();
  if (categoria === '1' || categoria === '2') {
    $('#trabalho-area').slideDown(); $('#trabalho-area2').slideUp();
  } else if (categoria === '4') {
    $('#trabalho-area').slideUp(); $('#trabalho-area2').slideDown();
  } else { $('#trabalho-area, #trabalho-area2').slideUp(); }
});

  // Ranking dinâmica
  const rankingAreas = {
    "I": ["Linguagens, Códigos e suas Tecnologias - LC", "Matemática e suas Tecnologias - MT", "Ciências da Natureza - CN", "Educação Ambiental e Engenharias - CH", "Robótica, Automação e Aplicação das TIC"],
    "II": ["Linguagens, Códigos e suas Tecnologias - LC", "Matemática e suas Tecnologias - MT", "Ciências da Natureza - CN", "Educação Ambiental e Engenharias - CH", "Robótica, Automação e Aplicação das TIC"],
    "III": [],
    "IV": ["Ensino Fundamental", "Ensino Médio"]
  };

  $('#ranking-categoria').on('change', function () {
    const cat = $(this).val();
    const areaSel = $('#ranking-area');
    areaSel.empty();
    if (cat && rankingAreas[cat].length > 0) {
      areaSel.append('<option selected disabled>-- Selecione a Área --</option>');
      rankingAreas[cat].forEach(a => areaSel.append('<option>' + a + '</option>'));
      areaSel.prop('disabled', false);
    } else {
      areaSel.append('<option selected>-- Sem área --</option>');
      areaSel.prop('disabled', true);
    }
    $('#ranking-tbody').html('<tr><td colspan="4" class="text-center text-muted">Selecione Categoria e Área para visualizar o ranking</td></tr>');
  });

  $('#ranking-area').on('change', function () {
    $('#ranking-tbody').html(
      '<tr><td>1º</td><td>Trabalho A</td><td>Escola X</td><td>95</td></tr>' +
      '<tr><td>2º</td><td>Trabalho B</td><td>Escola Y</td><td>92</td></tr>' +
      '<tr><td>3º</td><td>Trabalho C</td><td>Escola Z</td><td>90</td></tr>'
    );
  });
</script>

</body>
</html>