<?php
function gerarSenhaSegura($tamanho = 6) {
    $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()';
    $senha = '';
    $max = strlen($caracteres) - 1;
    for ($i = 0; $i < $tamanho; $i++) {
        $senha .= $caracteres[random_int(0, $max)];
    }
    return $senha;
}

$senha = gerarSenhaSegura();
echo $senha; // Ex: fG8@2bK!qLp9
?>