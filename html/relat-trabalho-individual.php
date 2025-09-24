<?php
require_once '../php/Connect.php';
require_once '../dompdf/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

if (
    !isset($_GET['id_trabalho']) || !is_numeric($_GET['id_trabalho']) ||
    !isset($_GET['id_jurado']) || !is_numeric($_GET['id_jurado'])
) {
    die("Parâmetros ausentes ou inválidos.");
}

$id_trabalho = (int) $_GET['id_trabalho'];
$id_jurado = (int) $_GET['id_jurado'];

$sql = "SELECT 
            t.titulo, 
            e.nome AS escola, 
            c.nome_categoria AS categoria,
            a.nome_area AS area
        FROM Trabalhos t
        LEFT JOIN Escolas e ON t.id_escolas = e.id_escolas
        LEFT JOIN Categorias c ON t.id_categoria = c.id_categoria
        LEFT JOIN Areas a ON t.id_areas = a.id_area
        WHERE t.id_trabalhos = :id_trabalho";

$stmt = $pdo->prepare($sql);
$stmt->execute(['id_trabalho' => $id_trabalho]);
$trabalho = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$trabalho) die("Trabalho não encontrado.");

$sql_jurado = "SELECT nome FROM Jurados WHERE id_jurados = :id_jurado";
$stmt_j = $pdo->prepare($sql_jurado);
$stmt_j->execute(['id_jurado' => $id_jurado]);
$jurado = $stmt_j->fetch(PDO::FETCH_ASSOC);

if (!$jurado) die("Jurado não encontrado.");

$sql_avaliacoes = "SELECT criterio, nota, comentario FROM Avaliacoes 
                   WHERE id_trabalho = :id_trabalho AND id_jurado = :id_jurado 
                   ORDER BY criterio";
$stmt2 = $pdo->prepare($sql_avaliacoes);
$stmt2->execute(['id_trabalho' => $id_trabalho, 'id_jurado' => $id_jurado]);
$avaliacoes = $stmt2->fetchAll(PDO::FETCH_ASSOC);

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

$avaliacoesIndexadas = [];
foreach ($avaliacoes as $av) {
    $avaliacoesIndexadas[$av['criterio']] = $av;
}

function toBase64Image($path)
{
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    if ($data === false) {
        return '';
    }
    $base64 = base64_encode($data);
    return "data:image/$type;base64,$base64";
}

$imgCearaCientifico = toBase64Image('/var/www/html/Sistema-Ceara-Cientifico-Crede-07/assets/img/cearacientifico.png');
$imgCrede7 = toBase64Image('/var/www/html/Sistema-Ceara-Cientifico-Crede-07/assets/img/crede7.png');
$imgCeara = toBase64Image('/var/www/html/Sistema-Ceara-Cientifico-Crede-07/assets/img/ceara.png');

ob_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Relatório de Avaliação</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        .info-header {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px 30px;
            margin-bottom: 25px;
            font-weight: 600;
        }

        table thead th {
            background-color: #4CAF50;
            color: white;
            font-weight: 700;
            padding: 10px;
            border: 1px solid #3e8e41;
            text-align: left;
        }

        table tbody td {
            border: 1px solid #ddd;
            padding: 8px;
            vertical-align: top;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .center {
            text-align: center;
            margin-bottom: 20px;
        }

        .logos {
            display: flex;
            justify-content: center;
            gap: 50px;
            margin-top: 30px;
        }

        .footer-logos {
            text-align: center;
            margin-top: 50px;
        }

        .footer-logos img {
            width: 140px;
            /* aumenta um pouco o tamanho */
            margin: 0 25px;
            display: inline-block;
            /* garante que fiquem lado a lado */
            vertical-align: middle;
        }
    </style>
</head>

<body>
    <div class="center">
        <img src="<?= $imgCearaCientifico ?>" style="max-width: 120px;"><br>
        <b>ETAPA REGIONAL - 2025</b>
    </div>

    <div class="info-header">
        <div><strong>Título:</strong> <?= htmlspecialchars($trabalho['titulo']) ?></div>
        <div><strong>Categoria:</strong> <?= htmlspecialchars($trabalho['categoria']) ?></div>
        <div><strong>Escola:</strong> <?= htmlspecialchars($trabalho['escola']) ?></div>
        <div><strong>Área:</strong> <?= htmlspecialchars($trabalho['area']) ?></div>
        <div><strong>Jurado:</strong> <?= htmlspecialchars($jurado['nome']) ?></div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Critério</th>
                <th>Avaliação</th>
                <th>Comentário</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($criterios as $num => $nome): ?>
                <tr>
                    <td><b><?= htmlspecialchars($nome) ?></b></td>
                    <td><?= isset($avaliacoesIndexadas[$num]) ? number_format($avaliacoesIndexadas[$num]['nota'], 2, ',', '.') : '' ?></td>
                    <td><?= isset($avaliacoesIndexadas[$num]) ? nl2br(htmlspecialchars($avaliacoesIndexadas[$num]['comentario'])) : '' ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="footer-logos">
        <img src="<?= $imgCrede7 ?>" alt="CREDE 7" />
        <img src="<?= $imgCeara ?>" alt="Governo do Ceará" />
    </div>

</body>

</html>
<?php
$html = ob_get_clean();

$options = new Options();
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$dompdf->stream("avaliacao_trabalho_{$id_trabalho}_jurado_{$id_jurado}.pdf", ["Attachment" => false]);
exit;
