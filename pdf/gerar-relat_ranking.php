<?php
require_once '../dompdf/vendor/autoload.php'; 

use Dompdf\Dompdf;

ob_start();
include(__DIR__ . '/../html/relat_ranking.php');
$html = ob_get_clean();

$dompdf = new Dompdf();
$dompdf->loadHtml($html);

$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$dompdf->stream("Ranking_geral.pdf", [
    "Attachment" => false
]);
exit;
