<?php
require_once '../dompdf/vendor/autoload.php'; 

use Dompdf\Dompdf;

ob_start();
include(__DIR__ . '/../html/relat_escola.php');
$html = ob_get_clean();

$dompdf = new Dompdf();
$dompdf->loadHtml($html);

$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$dompdf->stream("relatorio_escola_{$_GET['id_escola']}_.pdf", [
    "Attachment" => false
]);
exit;
