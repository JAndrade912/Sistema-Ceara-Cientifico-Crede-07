<?php
 session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Ceará Científico</title>
  <link rel="stylesheet" href="/assets/styles/login_adm.css">

  <link rel="stylesheet" href="boostrap/CSS/bootstrap.min.css">
  <script src="boostrap/JS/bootstrap.bundle.min.js"></script>
</head>

<body>
  <div class="fundo-arredondado"></div>
  <div class="container">
    <div class="left">
      <div class="logo"><img src="/assets/img/SACC.png"></div>
    </div>
    <div class="right">
      <div class="login-box">
        <div class="login-cont">
          <header>
            <b>Admin</b>
            <br>
            <br>
          </header>
          <form method="POST" action="php/auth/adm.php" id="loginForm">
            <div class="form-group">
              <label for="email">Email:</label>
              <input type="email" name="usuario" id="email" required placeholder="exemplo@dominio.com" />
            </div>
            <br>
            <div class="form-group">
              <label for="password">Senha:</label>
              <input type="password" name="password" id="password" required placeholder="Digite sua senha" />
            </div>
            <button type="submit">Entrar</button>

            <?php
              if(isset($_SESSION['erro_login'])){
                echo "<p class = 'text-danger'> " . $_SESSION['erro_login'] . "</p>";
                unset($_SESSION['erro_login']);
              }
            ?>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>

</html>