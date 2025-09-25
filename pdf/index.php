<?php
require __DIR__ . 'dompdf/vendor/autoload.php';

use Dompdf\Dompdf;

$sql = "SELECT * FROM Avaliacoes";

$dompdf = new Dompdf();

$dompdf->loadHtml('
<h1>Hello World</h1>
<p>This is a sample PDF generated using Dompdf.</p>
');

$dompdf->setPaper('A4', 'portrait');

$dompdf->render();

$dompdf->stream('document.pdf', ['Attachment' => false]);

header('Content-type: application/pdf');

echo $dompdf->output();
