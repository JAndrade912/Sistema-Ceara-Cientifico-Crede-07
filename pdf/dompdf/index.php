<?php
require __DIR__ . '/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Instância de options para configurar o dompdf
$options = new Options();
$options->setChroot(__DIR__); // define o diretório raiz
$options->setIsRemoteEnabled(true); // habilita o uso de recursos externos (imagens, css)

// Instância de dompdf
$dompdf = new Dompdf($options);

// Carrega o HTML diretamente do arquivo
$html = file_get_contents(__DIR__ . '/arquivo.html');

// Garante que o HTML está em UTF-8
$dompdf->loadHtml($html, 'UTF-8');

// Define o tamanho do papel e a orientação
$dompdf->setPaper('A4', 'portrait');

// Define a fonte padrão que suporta acentuação
$dompdf->set_option('defaultFont', 'DejaVu Sans');

// Renderiza o PDF
$dompdf->render();

// Cabeçalho para indicar que o conteúdo é PDF
header('Content-type: application/pdf');

// Mostra o PDF no navegador
echo $dompdf->output();
