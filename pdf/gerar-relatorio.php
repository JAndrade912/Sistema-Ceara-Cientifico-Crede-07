<?php
require_once '../dompdf/vendor/autoload.php'; 

use Dompdf\Dompdf;

ob_start();
include(__DIR__ . '/../html/relat-trabalho-individual.php');
$html = ob_get_clean();

$dompdf = new Dompdf();
$dompdf->loadHtml($html);

$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$dompdf->stream("relatorio_jurado_{$_GET['id_jurado']}_trabalho_{$_GET['id_trabalho']}.pdf", [
    "Attachment" => false
]);
exit;
