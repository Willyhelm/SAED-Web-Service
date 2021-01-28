<?php
  session_start();
  include_once 'utils.php';
  $html = '';
  $html2 = '';
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
      $html2 = '<br><p class="lead">Per prenotare una consulenza</p><p class="lead"><a href="login.php" class="btn btn-lg btn-primary">Accedi</a><p class="lead">oppure</p><a href="register.php" class="btn btn-lg btn-secondary">Registrati</a></p>';
  }
?>
<!DOCTYPE html>
<html lang="it">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Home - Studio Legale L. & S.</title>
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
          <a class="nav-link active" href="index.php">Home</a>
          <a class="nav-link" href="contacts.php">Contatti</a>
          <?php echo($html);?>
        </nav>
    </div>
  </header>

  <main role="main" class="inner cover">
    <br>
    <br>
    <h1 class="cover-heading">Proteggiamo le tue idee.</h1>
    <br>
    <img src="img/main.jpg" class="img-fluid" alt="Via Luigi Vanvitelli, 1, 06123 Perugia PG">
    <br>
    <br>
    <p class="lead">Diritto Civile, Diritto Penale, Diritto Amministrativo, Mediazione e Conciliazione, Transazioni stragiudiziali delle controversie, Famiglia, Matrimonio, Separazioni e divorzi, Diritto Minorile, Responsabilità da illecito, Colpa medica, Diffamazioni.</p>
    <?php echo($html2);?>
  </main>

  <footer class="mastfoot mt-auto">
    <div class="inner">
      <p>© 2019 Studio Legale L. & S.</p>
    </div>
  </footer>
</div>


</body></html>