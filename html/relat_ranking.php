<?php

// tratamento de erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// inciando sessão e importando o banco e a biblioteca do dompdf
session_start();
require_once '../php/Connect.php';
require_once '../dompdf/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

if (!isset($_GET['id_categoria']) || !is_numeric($_GET['id_categoria']) || !isset($_GET['id_areas']) || !is_numeric($_GET['id_areas'])) die("id_categoria ou id_areas não foi encontrado.");

$id_categoria = (int) $_GET['id_categoria'];
// condicional referente ao caso de area ser opcional (pesquisa junior)
$id_areas = null;
if (isset($_GET['id_areas']) && is_numeric($_GET['id_areas'])) {
    $id_areas = (int) $_GET['id_areas'];
}
// consulta para obter os trabalhos com suas respectivas escolas, categorias e áreas
$sql = "SELECT t.id_trabalhos, t.titulo, e.nome AS escola, e.focalizada, e.ide, c.nome_categoria AS categoria, a.nome_area AS area 
        FROM Trabalhos t 
        LEFT JOIN Escolas e ON t.id_escolas = e.id_escolas 
        LEFT JOIN Categorias c ON t.id_categoria = c.id_categoria 
        LEFT JOIN Areas a ON t.id_areas = a.id_area 
         WHERE t.id_categoria = :categoria";

$params = [':categoria' => $id_categoria];

if(!empty($id_areas)) {
    $sql .= " AND t.id_areas = :area";
    $params[':area'] = $id_areas;
}
$sql .= " ORDER BY t.id_trabalhos DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$dados = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <title>Relatório Ranking Geral</title>
  <style>
table {
    width: 90%;
    border-collapse: collapse;
    font-family: Arial, sans-serif;
    margin-left: 5%;
  }

th, td {
    border: 1px solid #1e1d1dff;
    padding: 8px;
    text-align: center;
}

h2 {
    text-align: center;
}

.criterio1{
    font-size: 17px;
    background-color: rgb(207, 205, 205);
    width: 5%;
}

.criterio{
    font-size: 17px;
    background-color: rgb(207, 205, 205);
    width: 18%;
}

.d-flex{
    background-color: #4C8F5A;
    width: 90%;
    margin-left: 5%; 
}

img{
   margin-left: 45%;
    width: 20%;
}

.logo{
    width: 90%;
    border: 1px solid #404040;
    margin-left: 5%;
    background-color: rgb(207, 205, 205);
}

.t1{
    font-size: 28px;
}

.t2{
    font-size: 20px;
}

</style>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../boostrap/CSS/bootstrap.min.css">
</head>
<body>
  <div class="text-center my-2">
    <img src="../assets/img/cearacientifico.png" alt="Ceará Científico" class="img-fluid" style="max-width: 120px;">
    <p><b>ETAPA REGIONAL - 2025</b></p>
  </div>
  <nav class="d-flex flex-column align-items-center bg-success mb-2 ">
    <div style="font-size: 15px;">
      <p><b>RESULTADO FINAL</b></p>
    </div>
    <div class="align-items-center flex-column d-flex">
      <p><b>Categoria: I - Ensino Médio</b></p>
      <p><b>Área: Ciências Humanas e Sociais Aplicadas</b></p>
    </div>
  </nav>

  <!-- Tabela -->
  <div class="container-fluid mb-5">
    <div class="table-responsive">
      <table class="table table-bordered table-striped">
        <thead class="table-secondary text-center align-middle">
          <tr id="tit">
            <th rowspan="1">Classificação</th>
            <th rowspan="3">Escola</th>
            <th rowspan="3">Título</th>
            <th rowspan="1">Nota final</th>
          </tr>
        </thead>
        <tbody class="text-center align-middle" style="font-size: 10px;">

          <?php
          
          $escolas = $pdo->query("SELECT id_escolas, nome FROM Escolas ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
          $categorias = $pdo->query("SELECT id_categoria, nome_categoria FROM Categorias ORDER BY nome_categoria")->fetchAll(PDO::FETCH_ASSOC);
          $areas = $pdo->query("SELECT id_area, nome_area FROM Areas ORDER BY nome_area")->fetchAll(PDO::FETCH_ASSOC);
          $trabalhos = $pdo->query("SELECT id_trabalhos, titulo FROM Trabalhos ORDER BY titulo")->fetchAll(PDO::FETCH_ASSOC);
          ?>
          <?php foreach ($dados as $index => $trab): ?>
            <tr>
              <td><?= $index + 1 ?></td>
              <td><?= htmlspecialchars($trab['escola']) ?></td>
              <td><?= htmlspecialchars($trab['titulo']) ?></td>
              <td>
                <?= $trab['nota_final'] !== null ? number_format($trab['nota_final'], 2, ',', '') : '-' ?>
              </td>
            </tr> 
            <?php endforeach; ?>
          <?php
          /*if (count($dados) > 0) {
            foreach ($dados as $trab) {
              echo '<tr>';
              echo '<td>' . '</td>';
              echo '<td>' . htmlspecialchars($trab['titulo']) . '</td>';
              echo '<td>' . htmlspecialchars($trab['escola']) . '</td>';
              echo '<td>' . htmlspecialchars($trab['categoria']) . '</td>';
              echo '<td>' . htmlspecialchars($trab['area']) . '</td>';
              echo '<td>' . (
                isset($trab['jurados'][1]['media_ponderada']) && $trab['jurados'][1]['media_ponderada'] !== null
                ? number_format($trab['jurados'][1]['media_ponderada'], 2, ',', '')
                : '-'
              ) . '</td>';

              echo '<td>' . (
                isset($trab['jurados'][2]['media_ponderada']) && $trab['jurados'][2]['media_ponderada'] !== null
                ? number_format($trab['jurados'][2]['media_ponderada'], 2, ',', '')
                : '-'
              ) . '</td>';

              echo '<td>' . (
                $trab['nota_final'] !== null
                ? number_format($trab['nota_final'], 2, ',', '')
                : '-'
              ) . '</td>';
              if (isset($trab['criterio_desempate']) && $trab['criterio_desempate'] !== null) {
                $crit = $trab['criterio_desempate'];
                echo '<td>' . htmlspecialchars($crit['criterio']) . '</td>';
              } else {
                echo '<td>-</td>';
              }
              echo '</tr>';
            }
          } else {
            echo '<tr><td colspan="8" class="text-center">Nenhum resultado encontrado</td></tr>';
          } 
        */    
        ?>
        </tbody>
        
      </table>
    </div>
  </div>

  <div class="d-flex justify-content-center" style="gap: 50px; margin-top: 20px;">
    <img src="../assets/img/crede7.png" style="max-width: 100px;">
    <img src="../assets/img/ceara.png" style="max-width: 100px;">
  </div>
</body>
</html>
<?php
/*
$html = ob_get_clean();

$options = new Options();
$options->set('isRemoteEnabled', false);
$options->set('defaultFont', 'DejaVu Sans');

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("Ranking_geral.pdf", ["Attachment" => false]);*/
?>