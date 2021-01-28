<?php
// Inizializza la sessione
session_start();

include_once 'utils.php';

// Se l'utente non è loggato reindirizzalo al login
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header('Location: loginavvocato.php', true, 307);
    exit(0);
}
// se un avvocato vuole accedere al profilo clienti
if ($_SESSION['tipo'] == 'cliente') {
    header('Location: profilo.php', true, 307);
    exit(0);
}
// se vi è stata una disdetta
if (isset($_POST['id'])) {
    $fields = array('id' => $_POST['id']);
    $response = curl_post('127.0.0.1/api/v1/prenotazione/delete.php', $fields);
    echo($response->message);
    header('Refresh: 1; URL=profilo.php');
    exit(0);
}
if (!empty($_POST['email_avvocato']) && !empty($_POST['data']) && !empty($_POST['descrizione'])) {
    $fields = array('email_avvocato' => $_POST['email_avvocato'],
                    'email_cliente' => $_SESSION['email'],
                    'data' => $_POST['data'],
                    'descrizione' => $_POST['descrizione']
                    );
    $response = curl_post('127.0.0.1/api/v1/prenotazione/create.php', $fields);
    echo($response->message);
    header('Refresh: 1; URL=profilo.php');
    exit(0);
}
$email_avvocato = $data = $descrizione = '';
$email_avvocato_err = $data_err = $descrizione_err = '';
$nominativo = $_SESSION['nome'] . ' ' . $_SESSION['cognome'];
?>
<!DOCTYPE html>
<html lang="it">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title><?php echo($nominativo);?> - Studio Legale L. & S.</title>
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
        <a class="nav-link" href="contacts.php">Contatti</a>
        <a class="nav-link text-primary active" href="profiloavvocato.php"><?php echo($nominativo); ?></a>
        <a class="nav-link text-danger" href="logout.php">Esci</a>
      </nav>
    </div>
  </header>

  <main role="main" class="inner cover">
  <div class="wrapper">
  <div class="page-header">
        <h1>Benvenuto, <b><?php echo htmlspecialchars($_SESSION['nome'] . ' ' . $_SESSION['cognome']); ?></b>.</h1>
    </div>
    <br>
    <h2>Consulenze prenotate presso Avv. <?php echo($nominativo);?></h2>
    <br>
    <?php
    $json = json_decode(curl_get("127.0.0.1/api/v1/prenotazione/read.php?email_avvocato={$_SESSION['email']}"));
    if (isset($json->message)) {
        echo $json->message;
    } else {
        require_once "../database.php";
        $database = new Database();
        $db = $database->getConnection();
        echo('<table class="table table-dark"><tr><th>Cliente</th><th>Data</th><th>Descrizione</th><th></th></tr>');
        foreach ($json->results as $key => $value) {
            $cliente = 'Errore di connessione.';
            $sql = "SELECT `nome`, `cognome` FROM `cliente` WHERE `email` = :email";
            if ($stmt = $db->prepare($sql)) {
                $stmt->bindParam(":email", $value->email_cliente, PDO::PARAM_STR);
                if ($stmt->execute()) {
                    if ($row = $stmt->fetch()) {
                        $cliente = $row['nome'] . ' ' . $row['cognome'];
                    }
                }
            }
            echo('<tr><td>' . $cliente . '</td><td>' . $value->data . '</td><td>' . $value->descrizione . '</td><td>' . '<form action="profilo.php" method="post"><input type="hidden" name="id" value="' . $value->id . '"><input class="btn btn-danger" type="submit" value="Disdici"></form>' . '</td></tr>');
        }
        echo('</table>');
    }
    ?>
    </div>
  </main>

  <footer class="mastfoot mt-auto">
    <div class="inner">
      <p>© 2019 Studio Legale L. & S.</p>
    </div>
  </footer>
</div>


</body></html>