<?php
  session_start();
  include_once 'utils.php';
  $html = '';
  $nominativo = '';
  if (session_exists($_SESSION)) {
      $nominativo = $_SESSION['nome'] . ' ' . $_SESSION['cognome'];
      if ($_SESSION['tipo'] == 'cliente') {
          $html = '<a class="nav-link text-primary" href="profilo.php">' . $nominativo . '</a><a class="nav-link text-danger" href="logout.php">Esci</a>';
      } else {
          $html = '<a class="nav-link text-primary" href="profiloavvocato.php">' . $nominativo . '</a><a class="nav-link text-danger" href="logout.php">Esci</a>';
      }
  } else {
      $html = '<a class="nav-link" href="loginavvocato.php" class="btn btn-lg btn-secondary">Accesso avvocati</a>';
  }
?>
<!DOCTYPE html>
<html lang="it">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Contatti - Studio Legale L. & S.</title>
    <!-- Bootstrap core CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>
    <!-- Custom styles for this template -->
    <link href="css/cover.css" rel="stylesheet">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
  </head>
  <body class="text-center">
    <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
  <header class="masthead mb-auto">
    <div class="inner">
    <h3 class="masthead-brand"><a class="navbar-brand" href="index.php">
          <img src="img/logo.png" alt="">
          </a>Studio Legale L. & S.
        </h3>
      <nav class="nav nav-masthead justify-content-center">
        <a class="nav-link" href="index.php">Home</a>
        <a class="nav-link active" href="contacts.php">Contatti</a>
        <?php echo($html);?>
      </nav>
    </div>
  </header>

  <main role="main" class="inner cover">
    <p class="h4">Studio Legale L. & S.</p>
    <br>
    <p>Avv. Andrea L.</p>
    <p>Avv. Guglielmo S.</p>
    <p>Avv. Sergio T.</p>
    <a href="tel:+390755855001" class="btn btn-link">075 585 5001</a>
    <br>
    <a href="https://www.google.it/maps/place/Via+Luigi+Vanvitelli,+1,+06123+Perugia+PG/@43.1159309,12.3829206,17z/data=!3m1!4b1!4m5!3m4!1s0x132ea0823f9d80ab:0x67dd26ff73307c4d!8m2!3d43.115927!4d12.3851093" class="btn btn-link" target="_blank">Via Luigi Vanvitelli, 1, 06123 Perugia PG</a>
    <br>
    <a href="mailto:info@slls.it" class="btn btn-link">info@slls.it</a>
    <br>
    <br>
    <img src="img/staticmap.png" class="img-fluid" alt="Via Luigi Vanvitelli, 1, 06123 Perugia PG">
  </main>

  <footer class="mastfoot mt-auto">
    <div class="inner">
      <p>© 2019 Studio Legale L. & S.</p>
    </div>
  </footer>
</div>


</body></html>