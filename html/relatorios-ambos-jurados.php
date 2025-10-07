<?php
require_once '../php/Connect.php';
require_once '../dompdf/autoload.inc.php';

use Dompdf\Dompdf;

function imgToBase64($path)
{
  if (file_exists($path)) {
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    return 'data:image/' . $type . ';base64,' . base64_encode($data);
  }
  return '';
}

$imgCearaCientifico = imgToBase64('C:/xampp/htdocs/GitHub/Sistema-Ceara-Cientifico-Crede-07/assets/img/cearacientifico.png');
$imgCrede7 = imgToBase64('C:/xampp/htdocs/GitHub/Sistema-Ceara-Cientifico-Crede-07/assets/img/crede7.png');
$imgCeara = imgToBase64('C:/xampp/htdocs/GitHub/Sistema-Ceara-Cientifico-Crede-07/assets/img/ceara.png');
$imgLogo = imgToBase64('C:/xampp/htdocs/GitHub/Sistema-Ceara-Cientifico-Crede-07/assets/img/b76f995f-d85d-4d51-bf6b-47dd645dad78.png');

$mapa_criterios = [
  1 => 'criatividade',
  2 => 'relevancia',
  3 => 'conhecimento',
  4 => 'impacto',
  5 => 'metodologia',
  6 => 'clareza',
  7 => 'banner',
  8 => 'caderno',
  9 => 'processo',
  10 => 'total'
];

$criterios = [
  'criatividade' => 'Criatividade',
  'relevancia' => 'Relevância',
  'conhecimento' => 'Conhecimento',
  'impacto' => 'Impacto',
  'metodologia' => 'Metodologia',
  'clareza' => 'Clareza',
  'banner' => 'Banner',
  'caderno' => 'Caderno',
  'processo' => 'Processo'
];

// Receber filtros via URL
$catId = isset($_GET['catId']) ? intval($_GET['catId']) : 0;
$areaId = isset($_GET['areaId']) ? intval($_GET['areaId']) : 0;

// Montar SQL dinâmico
$sql = "
SELECT 
    t.id_trabalhos,
    t.titulo,
    e.nome AS escola,
    j.id_jurados,
    j.usuario AS user_jurado,
    av.criterio,
    av.nota,
    c.nome_categoria AS categoria,
    a.nome_area AS area
FROM Trabalhos t
LEFT JOIN Escolas e ON t.id_escolas = e.id_escolas
LEFT JOIN Avaliacoes av ON av.id_trabalho = t.id_trabalhos
LEFT JOIN Jurados j ON j.id_jurados = av.id_jurado
LEFT JOIN Categorias c ON t.id_categoria = c.id_categoria
LEFT JOIN Areas a ON t.id_areas = a.id_area
WHERE 1
";

$params = [];
if ($catId > 0) {
  $sql .= " AND t.id_categoria = ?";
  $params[] = $catId;
}
if ($areaId > 0) {
  $sql .= " AND t.id_areas = ?";
  $params[] = $areaId;
}

$sql .= " ORDER BY t.id_trabalhos, av.criterio, j.id_jurados";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

$trabalhos = [];
$jurados_por_criterio = [];
foreach ($dados as $linha) {
  $id = $linha['id_trabalhos'];
  $id_jurado = $linha['id_jurados'];
  $user_jurado = $linha['user_jurado'] ?? 'Sem jurado';
  $nota = $linha['nota'] ?? 0;

  $criterio = isset($mapa_criterios[$linha['criterio']]) ? $mapa_criterios[$linha['criterio']] : null;

  $trabalhos[$id]['titulo'] = $linha['titulo'];
  $trabalhos[$id]['escola'] = $linha['escola'];
  $trabalhos[$id]['categoria'] = $linha['categoria'] ?? 'Sem categoria';
  $trabalhos[$id]['area'] = $linha['area'] ?? 'Sem área';

  if ($criterio && $criterio != 'total') {
    $trabalhos[$id]['notas'][$criterio][$id_jurado] = $nota;
    $jurados_por_criterio[$criterio][$id_jurado] = $user_jurado;
  }
}

// Gerar HTML
ob_start();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="utf-8">
  <title>Relatório dos Jurados</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 10px;
    }

    .text-center {
      text-align: center;
    }

    .table-container {
      display: flex;
      justify-content: center;
      margin-top: 10px;
    }

    table {
      border-collapse: collapse;
      font-size: 9px;
      width: 100%;
      max-width: 1200px;
    }

    th,
    td {
      border: 1px solid #000;
      padding: 4px;
      text-align: center;
    }

    th {
      background-color: #d1e7dd;
    }

    .header-title {
      font-size: 14px;
      font-weight: bold;
    }

    .sub-title {
      font-size: 11px;
      font-weight: bold;
      margin: 2px 0;
    }

    .footer-images {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin-top: 20px;
    }

    .footer-images img,
    .header-image {
      max-width: 130px;
      display: inline-block;
      margin: 0 10px;
    }
  </style>
</head>

<body>
  <div class="text-center">
    <?php if ($imgCearaCientifico): ?>
      <img src="<?= $imgCearaCientifico ?>" style="max-width:150px; margin-bottom:10px;">
    <?php endif; ?>
    <p><b>ETAPA REGIONAL - 2025</b></p>
  </div>

  <?php
  $primeiro_trabalho = reset($trabalhos);
  $categoria = $primeiro_trabalho['categoria'] ?? 'Sem categoria';
  $area = $primeiro_trabalho['area'] ?? 'Sem área';
  ?>

  <div class="text-center" style="background-color:#198754; color:#fff; padding:6px;">
    <div class="header-title">PLANILHA DE AVALIAÇÃO DOS JURADOS</div>
    <div class="sub-title">CATEGORIA: <?= htmlspecialchars($categoria) ?></div>
    <div class="sub-title">ÁREA: <?= htmlspecialchars($area) ?></div>
  </div>

  <div class="table-container">
    <table>
      <thead>
        <tr>
          <th rowspan="2">Escola</th>
          <th rowspan="2">Título</th>
          <?php foreach ($criterios as $c => $label): ?>
            <th colspan="<?= count($jurados_por_criterio[$c] ?? [1, 2]) ?>"><?= $label ?></th>
          <?php endforeach; ?>
          <th rowspan="2">Nota final</th>
        </tr>
        <tr>
          <?php foreach ($criterios as $c => $label):
            $jurados = $jurados_por_criterio[$c] ?? [0 => 'Jurado 1', 1 => 'Jurado 2'];
            foreach ($jurados as $id_j => $nome_j): ?>
              <th><?= htmlspecialchars($nome_j) ?></th>
          <?php endforeach;
          endforeach; ?>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($trabalhos as $t): ?>
          <tr>
            <td><?= htmlspecialchars($t['escola']) ?></td>
            <td><?= htmlspecialchars($t['titulo']) ?></td>
            <?php
            $nota_total = 0;
            $total_jurados = 0;
            foreach ($criterios as $c => $label) {
              $notas_criterio = $t['notas'][$c] ?? [];
              $jurados = $jurados_por_criterio[$c] ?? [0 => 'Jurado 1', 1 => 'Jurado 2'];
              foreach ($jurados as $id_j => $nome_j) {
                $nota = $notas_criterio[$id_j] ?? 0;
                $nota_total += $nota;
                $total_jurados++;
                echo "<td>$nota</td>";
              }
            }
            $media_final = $total_jurados ? number_format($nota_total / $total_jurados, 2) : 0;
            ?>
            <td><?= $media_final ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <div class="footer-images">
    <?php if ($imgCrede7): ?><img src="<?= $imgCrede7 ?>"><?php endif; ?>
    <?php if ($imgCeara): ?><img src="<?= $imgCeara ?>"><?php endif; ?>
    <?php if ($imgLogo): ?><img src="<?= $imgLogo ?>"><?php endif; ?>
  </div>
</body>

</html>

<?php
$html = ob_get_clean();
$dompdf = new Dompdf();
$dompdf->set_option('isRemoteEnabled', true);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("relatorio_jurados.pdf", ["Attachment" => false]);
?>