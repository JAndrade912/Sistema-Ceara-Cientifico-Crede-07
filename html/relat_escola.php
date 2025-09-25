<?php
require_once '../php/Connect.php';
require_once '../dompdf/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

if (!isset($_GET['id_escola']) || !is_numeric($_GET['id_escola'])) die("id_escola não foi fornecido.");

$id_escola = (int) $_GET['id_escola'];

$sql = "SELECT
        t.titulo,
        j.id_jurados,
        j.usuario AS nome_jurado,
        av.criterio,
        av.nota
    FROM Trabalhos t
    LEFT JOIN Avaliacoes av ON av.id_trabalho = t.id_trabalhos
    LEFT JOIN Jurados j ON j.id_jurados = av.id_jurado
    WHERE t.id_escolas = ?
";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id_escola]);
$dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$dados) die("Verifique se o id_escola existe no banco de dados, ou se a escola possue trabalhos atrelados a ela.");

$trabalhos = [];
foreach ($dados as $linha) {
  $titulo = $linha['titulo'];
  $jurado = $linha['id_jurados'];

  if (!isset($trabalhos[$titulo])) {
    $trabalhos[$titulo] = [];
  }

  if (!isset($trabalhos[$titulo][$jurado])) {
    $trabalhos[$titulo][$jurado] = [
      'nome' => $linha['nome_jurado'],
      'criterios' => [],
      'total' => 0
    ];
  }

  if ($linha['criterio']) {
    $trabalhos[$titulo][$jurado]['criterios'][$linha['criterio']] = $linha['nota'];
    $trabalhos[$titulo][$jurado]['total'] += $linha['nota'];
  }
}

$criterios = [
  1 => "Criatividade e Inovação",
  2 => "Relevância da pesquisa",
  3 => "Conhecimento científico fundamentado e contextualização do problema abordado",
  4 => "Impacto para a construção de uma sociedade que promova os saberes científicos em tempos de crise climática global",
  5 => "Metodologia científica conectada com os objetivos, resultados e conclusões",
  6 => "Clareza e objetividade na linguagem apresentada",
  7 => "Banner",
  8 => "Caderno de campo",
  9 => "Processo participativo e solidário"
];

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <title>Relatório por Escola</title>
  <meta charset="utf-8">
  <link rel="stylesheet" href="../boostrap/CSS/bootstrap.min.css">
  <script src="../boostrap/JS/bootstrap.bundle.min.js"></script>
</head>

<body>

  <div class="text-center my-2">
    <img src="../assets/img/cearacientifico.png" alt="Ceará Científico" class="img-fluid" style="max-width: 120px;">
    <p><b>ETAPA REGIONAL - 2025</b></p>
  </div>

  <nav class="d-flex flex-column align-items-center bg-success mb-2 ">
    <div style="font-size: 15px;">
      <p><b>PLANILHA DE AVALIAÇÃO POR ESCOLA</b></p>
    </div>
  </nav>
  <?php
  $sql_jurados = "SELECT DISTINCT j.usuario 
                FROM Jurados j
                INNER JOIN Avaliacoes av ON av.id_jurado = j.id_jurados
                INNER JOIN Trabalhos t ON t.id_trabalhos = av.id_trabalho
                WHERE t.id_escolas = :id_escola
                ORDER BY j.usuario ASC";
  $stmt_jurados = $pdo->prepare($sql_jurados);
  $stmt_jurados->execute(['id_escola' => $id_escola]);
  $jurados = $stmt_jurados->fetchAll(PDO::FETCH_COLUMN);
  ?>
  <div class="container-fluid mb-5">
    <div class="table-responsive">
      <table class="table table-bordered table-striped">
        <thead class="table-secondary text-center align-middle" style="font-size: 8px;">
          <tr>
            <th rowspan="2">Título</th>
            <?php foreach ($criterios as $nome): ?>
              <th colspan="2"><?= $nome ?></th>
            <?php endforeach; ?>
            <th colspan="2">Total individual</th>
            <th rowspan="2">Nota final</th>
          </tr>
          <tr>
            <?php foreach ($criterios as $criterio): ?>
              <?php foreach ($jurados as $usuario): ?>
                <th class="table-success"><?= htmlspecialchars($usuario) ?></th>
              <?php endforeach; ?>
            <?php endforeach; ?>
            <th> Jurado 1</th>
            <th> Jurado 2</th>
          </tr>
        </thead>
        <tbody class="text-center align-middle" style="font-size: 9px;">
          <?php foreach ($trabalhos as $titulo => $jurados): ?>
            <tr>
              <td><?= htmlspecialchars($titulo) ?></td>

              <?php foreach ($criterios as $id => $usuario): ?>
                <?php foreach ([0, 1] as $i): ?>
                  <td>
                    <?php
                    $juradoIds = array_keys($jurados);
                    $juradoId = $juradoIds[$i] ?? null;
                    echo $juradoId && isset($jurados[$juradoId]['criterios'][$id])
                      ? $jurados[$juradoId]['criterios'][$id]
                      : "-";
                    ?>
                  </td>
                <?php endforeach; ?>
              <?php endforeach; ?>

              <?php foreach ([0, 1] as $i): ?>
                <td>
                  <?php
                  $juradoIds = array_keys($jurados);
                  $juradoId = $juradoIds[$i] ?? null;
                  echo $juradoId ? $jurados[$juradoId]['total'] : "-";
                  ?>
                </td>
              <?php endforeach; ?>

              <td>
                <?php
                $soma = array_sum(array_column($jurados, 'total'));
                $qtd = count($jurados);
                echo $qtd ? round($soma / $qtd, 2) : "-";
                ?>
              </td>
            </tr>
          <?php endforeach; ?>
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