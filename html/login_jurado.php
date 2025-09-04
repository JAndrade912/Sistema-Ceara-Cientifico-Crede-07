<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SACC - Home</title>
  <link rel="stylesheet" href="../assets/styles/index.css">
  <link rel="stylesheet" href="../boostrap/CSS/bootstrap.min.css">
  <link rel="alternate" href="../assets/img/SIMBOLO.png" type="application/atom+xml" title="Atom">
  <link rel="stylesheet" href="../boostrap/CSS/bootstrap.min.css">
  <script src="../boostrap/JS/bootstrap.bundle.min.js"></script>
</head>
<body>
  <nav class="navbar bg-body-tertiary">
    <div class="d-flex">
      <a class="navbar-brand" href="#">
        <div id="corpo-img"> 
          <img src="../assets/img/SACC.png" alt="Logo" id="img-sacc" class="d-inline-block align-text-top">
        </div>
      </a>
    </div>
  </nav>
  <div class="container d-flex justify-content-center align-items-center">
    
    <div class="container d-flex justify-content-center align-items-center">
    <div class="container-fluid" id="login-box">
      <div class="right">
      <div class="login-box">
        <div class="login-cont">
          <h2><b>Jurado</b></h2><br>
          <form method="POST" action="" id="loginForm">
            <div class="form-group">
              <label for="usuario"><b>Usuário:</b></label>
              <br>
              <input type="text" name="usuario" id="usuario" />
            </div>
              <br>
            <div class="form-group">
              <label for="password"><b>Senha:</b></label>
                <br>
              <input type="password" name="password" id="password" required placeholder="Digite sua senha"/>
            </div>
            <button type="submit" id="bt1">Entrar</button>
          </form>
        </div>
      </div>
    </div>
      <div class="logos">
        <img src="../assets/img/crede7.png" alt="Crede 7 Canindé">
      </div>
    </div>
  </div>
  </div>
</body>
</html>