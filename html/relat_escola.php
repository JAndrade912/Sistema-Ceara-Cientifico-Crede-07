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

function toBase64Image($path)
{
    if (!file_exists($path)) return '';
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    if ($data === false) {
        return '';
    }
    $base64 = base64_encode($data);
    return "data:image/$type;base64,$base64";
}

$stmtEsc = $pdo->prepare("SELECT nome FROM Escolas WHERE id_escolas = ?");
$stmtEsc->execute([$id_escola]);
$result = $stmtEsc->fetch(PDO::FETCH_ASSOC);
$userName = $result ? $result['nome'] : 'Escola';

$imgCearaCientifico = toBase64Image(__DIR__.'/../assets/img/cearacientifico.png');
$imgCrede7 = toBase64Image(__DIR__.'/../assets/img/crede7.png');
$imgCeara = toBase64Image(__DIR__.'/../assets/img/ceara.png');
ob_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <title>Relatório por Escola</title>
  <style>
    table tbody td {
        vertical-align: top;
        word-wrap: break-word; 
        word-break: break-word;     
        white-space: pre-wrap;
        max-width: 400px;   
        padding: 8px;
        border: 1px solid #ddd;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
  body {
    font-family: 'DejaVu Sans', sans-serif;
    font-size: 10px;
  }
  table {
    border-collapse: collapse;
    width: 100%;
  }
  table th, table td {
    border: 1px solid #ddd;
    padding: 4px;
    text-align: center;
  }
</style>

  <link rel="stylesheet" href="../boostrap/CSS/bootstrap.min.css">
</head>

<body>

  <div class="text-center my-2">
    <img src="<?= $imgCearaCientifico ?>" alt="Ceará Científico" class="img-fluid" style="max-width: 120px;">
    <p><b>ETAPA REGIONAL - 2025</b></p>
  </div>

  <nav class="d-flex flex-column align-items-center bg-success mb-2 ">
    <div style="font-size: 15px;">
      <p style="color: white;"><b>PLANILHA DE AVALIAÇÃO DE <?= $userName?></b></p>
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
    <img src=<?= $imgCrede7 ?> style="max-width: 100px;">
    <img src=<?= $imgCeara ?> style="max-width: 100px;">
  </div>

</body>
</html>
<?php
$html = ob_get_clean();

$options = new Options();
$options->set('isRemoteEnabled', false);
$options->set('defaultFont', 'DejaVu Sans'); 

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("relatorio_escola_{$_GET['id_escola']}_.pdf", [
    "Attachment" => false
]);
exit;
