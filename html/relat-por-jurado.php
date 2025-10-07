<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../dompdf/vendor/autoload.php';
require_once '../php/Connect.php';

use Dompdf\Dompdf;

$id_jurado = isset($_GET['id_jurado']) ? intval($_GET['id_jurado']) : 0;
if ($id_jurado <= 0) die("ID do jurado inválido.");

$juradoQuery = $pdo->prepare("SELECT nome FROM Jurados WHERE id_jurados = ?");
$juradoQuery->execute([$id_jurado]);
$jurado = $juradoQuery->fetch(PDO::FETCH_ASSOC);
if (!$jurado) die("Jurado não encontrado.");

$avaliacoesQuery = $pdo->prepare("
    SELECT 
        T.id_trabalhos,
        T.titulo,
        E.nome AS escola,
        A.criterio,
        A.nota
    FROM Avaliacoes A
    JOIN Trabalhos T ON A.id_trabalho = T.id_trabalhos
    JOIN Escolas E ON T.id_escolas = E.id_escolas
    WHERE A.id_jurado = ?
    ORDER BY T.id_trabalhos, A.criterio
");
$avaliacoesQuery->execute([$id_jurado]);
$avaliacoesRaw = $avaliacoesQuery->fetchAll(PDO::FETCH_ASSOC);

// Pesos por critério
$pesos = [
  1 => 1,
  2 => 1,
  3 => 1.5,
  4 => 1,
  5 => 2,
  6 => 1,
  7 => 1,
  8 => 1,
  9 => 0.5
];

// Processar os dados por trabalho
$trabalhos = [];

foreach ($avaliacoesRaw as $row) {
  $id = $row['id_trabalhos'];
  $criterio = $row['criterio'];
  $nota = $row['nota'];
  $peso = isset($pesos[$criterio]) ? $pesos[$criterio] : 1;

  if (!isset($trabalhos[$id])) {
    $trabalhos[$id] = [
      'titulo' => $row['titulo'],
      'escola' => $row['escola'],
      'notas' => [],
      'ponderada_soma' => 0,
      'peso_total' => 0,
      'nota_final' => 0
    ];
  }

  $trabalhos[$id]['notas'][$criterio] = $nota;
  $trabalhos[$id]['ponderada_soma'] += $nota * $peso;
  $trabalhos[$id]['peso_total'] += $peso;
}

foreach ($trabalhos as &$t) {
  if ($t['peso_total'] > 0) {
    $t['nota_final'] = ($t['ponderada_soma'] / $t['peso_total']) * 10;
  } else {
    $t['nota_final'] = 0;
  }

  unset($t['ponderada_soma'], $t['peso_total']);
}
unset($t);

function imgBase64($path)
{
  if (!file_exists($path)) return '';
  $type = pathinfo($path, PATHINFO_EXTENSION);
  $data = file_get_contents($path);
  return 'data:image/' . $type . ';base64,' . base64_encode($data);
}

$logo1 = imgBase64(__DIR__ . '/../assets/img/cearacientifico.png');
$logo2 = imgBase64(__DIR__ . '/../assets/img/crede7.png');
$logo3 = imgBase64(__DIR__ . '/../assets/img/ceara.png');

ob_start();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <title>Relatório do Jurado</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      font-size: 12px;
      margin: 0;
      padding: 20px;
    }

    .footer {
      text-align: center;
      margin-top: 50px;
      margin-bottom: 40px;
    }

    .assinatura {
      margin-top: 40px;
      margin-bottom: 60px;
      text-align: center;
    }

    .logos {
      margin-top: 60px;
      text-align: center;
    }

    .header {
      text-align: center;
      margin-bottom: 30px;
    }

    .header img {
      display: block;
      margin: 0 auto 10px auto;
      height: 80px;
    }

    table {
      width: 95%;
      margin: 20px auto;
      border-collapse: collapse;
    }

    th,
    td {
      font-size: 11px;
      padding: 6px 8px;
      border: 1px solid #000;
      text-align: center;
    }

    th.nota-final,
    td.nota-final {
      width: 70px;
      font-weight: bold;
    }

    table tbody td {
      word-wrap: break-word;
      word-break: break-word;
    }

    .logos img {
      height: 70px;
      margin: 0 25px;
    }

    p {
      word-break: break-all;
    }
  </style>
</head>

<body>

  <div class="header">
    <img src="<?= $logo1 ?>" alt="Logo" style="height: 80px;"><br>
    <strong>ETAPA REGIONAL - 2025</strong><br><br>
    <strong>PLANILHA DE AVALIAÇÃO POR JURADO</strong><br>
    <strong>JURADO: <?= htmlspecialchars($jurado['nome']) ?></strong>
  </div>

  <table>
    <thead>
      <tr>
        <th>Escola</th>
        <th>Título</th>
        <th>Criatividade</th>
        <th>Relevância</th>
        <th>Conhecimento</th>
        <th>Impacto</th>
        <th>Metodologia</th>
        <th>Clareza</th>
        <th>Banner</th>
        <th>Caderno</th>
        <th>Participação</th>
        <th>Nota Final</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($trabalhos as $t): ?>
        <tr>
          <td><?= htmlspecialchars($t['escola']) ?></td>
          <td><?= htmlspecialchars($t['titulo']) ?></td>
          <?php
          for ($i = 1; $i <= 9; $i++) {
            echo "<td>" . (isset($t['notas'][$i]) ? number_format($t['notas'][$i], 2) : '-') . "</td>";
          }
          ?>
          <td><strong><?= number_format($t['nota_final'], 2) ?></strong></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <div class="footer">
    <p>Canindé, 9 de Outubro de 2025</p>
  </div>

  <div class="assinatura">
    <hr style="width: 40%;">
    <p>Avaliador(a)</p>
  </div>

  <div class="logos">
    <img src="<?= $logo2 ?>" alt="CREDE">
    <img src="<?= $logo3 ?>" alt="CEARÁ">
  </div>

</body>

</html>

<?php
$html = ob_get_clean();

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("relatorio_jurado.pdf", ["Attachment" => false]);
