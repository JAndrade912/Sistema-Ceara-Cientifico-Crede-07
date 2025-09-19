<?php
require __DIR__ . '/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// instancia de options, para configurar o dompdf
$options = new Options();
$options->setChroot(__DIR__); // define o diretorio raiz
$options->setIsRemoteEnabled(true); // habilita o uso de recursos externos, como imagens e css
//instancia de dompdf, usada toda vez que for gerar um pdf
$dompdf = new Dompdf($options);

$dompdf->loadHtmlFile(__DIR__.'/arquivo.html');
// metodo render gera o pdf, ja renderizando ele
$dompdf->render();

// indicando para o navegador por meio do cabeçalho do arquivo que este conteudo é um pdf
header('Content-type: application/pdf'); 

// imprime o conteudo do arquivo pdf na tela
echo $dompdf->output();