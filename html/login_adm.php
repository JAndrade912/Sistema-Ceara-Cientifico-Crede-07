<?php
session_start();
$error = '';

if (isset($_SESSION['login_error'])) {
  $error = $_SESSION['login_error'];
  unset($_SESSION['login_error']);
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Ceará Científico</title>
  <link rel="stylesheet" href="../assets/styles/login_adm.css">

  <link rel="stylesheet" href="./boostrap/CSS/bootstrap.min.css">
  <script src="./boostrap/JS/bootstrap.bundle.min.js"></script>
</head>

<body>
  <div class="fundo-arredondado"></div>
  <div class="container">
    <div class="left">
      <div class="logo"><img src="../assets/img/SACC.png"></div>
    </div>
    <div class="right">
      <div class="login-box">
        <div class="login-cont">
          <header>
            <b>Admin</b>
            <br>
            <br>
          </header>
          <form method="POST" action="../php/adm.php" id="loginForm">
            <div class="form-group">
              <label for="usuario">Email:</label>
              <input type="text" name="usuario" id="usuario" required placeholder="Digite seu usuário" />
            </div>
            <br>
            <div class="form-group">
              <label for="senha">Senha:</label>
              <input type="password" name="senha" id="senha" required placeholder="Digite sua senha" />
            </div>
            <button type="submit">Entrar</button>
          </form>
          <?php if (!empty($error)) : ?>
            <div style="color: red; margin-top: 10px; text-align: center;">
              <?= htmlspecialchars($error) ?>
            </div>
          <?php endif; ?>

        </div>
      </div>
    </div>
  </div>
</body>

</html>