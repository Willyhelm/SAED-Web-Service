<?php
// Inizializza la sessione
session_start();

include_once 'utils.php';

// Se l'utente non è loggato reindirizzalo al login
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header('Location: login.php', true, 307);
    exit(0);
}
// se un avvocato vuole accedere al profilo clienti
if ($_SESSION['tipo'] == 'avvocato') {
    header('Location: profiloavvocato.php', true, 307);
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

      .lbl {
        float: left;
      }

      .frm {
        width: 40%;
        margin: 0 auto;
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
        <a class="nav-link text-primary active" href="profilo.php"><?php echo($nominativo); ?></a>
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
    <h2>Consulenze prenotate</h2>
    <br>
    <?php
    $json = json_decode(curl_get("127.0.0.1/api/v1/prenotazione/read.php?email_cliente={$_SESSION['email']}"));
    if (isset($json->message)) {
        echo $json->message;
    } else {
        require_once "../database.php";
        $database = new Database();
        $db = $database->getConnection();
        echo('<table class="table table-dark"><tr><th>Avvocato</th><th>Data</th><th>Descrizione</th><th></th></tr>');
        foreach ($json->results as $key => $value) {
            $avvocato = 'Errore di connessione.';
            $sql = "SELECT `nome`, `cognome` FROM `avvocato` WHERE `email` = :email";
            if ($stmt = $db->prepare($sql)) {
                $stmt->bindParam(":email", $value->email_avvocato, PDO::PARAM_STR);
                if ($stmt->execute()) {
                    if ($row = $stmt->fetch()) {
                        $avvocato = $row['nome'] . ' ' . $row['cognome'];
                    }
                }
            }
            echo('<tr><td>' . $avvocato . '</td><td>' . $value->data . '</td><td>' . $value->descrizione . '</td><td>' . '<form action="profilo.php" method="post"><input type="hidden" name="id" value="' . $value->id . '"><input class="btn btn-danger" type="submit" value="Disdici"></form>' . '</td></tr>');
        }
        echo('</table>');
    }

    // Valida nome
    if (isset($_POST["email_avvocato"]) && empty(trim($_POST["email_avvocato"]))) {
        $email_avvocato_err = "Scegli un avvocato.";
    }
    // Valida data
    if (isset($_POST["data"]) && empty(trim($_POST["data"]))) {
        $data_err = "Scegli una data.";
    }
    // Valida descrizione
    if (isset($_POST["descrizione"]) && empty(trim($_POST["descrizione"]))) {
        $descrizione_err = "Inserisci una descrizione.";
    }
    ?>
    <!-- form -->
    <br>
    <br>
    <h2>Prenota consulenza</h2>
    <p>Completa tutti i campi per prenotare una consulenza presso lo Studio Legale L. & S..</p>
    <div class="frm">
      <form action="profilo.php" method="post">
          <div class="form-group <?php echo (!empty($data_err)) ? 'has-error' : ''; ?>">
              <label class="lbl">Avvocato</label>
              <select class="form-control" name="email_avvocato">
                  <option value="" hidden selected>Scegli un avvocato</option>
                  <option value="andrea@slls.it">Andrea L.</option>
                  <option value="guglielmo@slls.it">Guglielmo S.</option>
                  <option value="sergio@slls.it">Sergio Tasso</option>
              </select>
              <span class="help-block text-danger"><?php echo $email_avvocato_err; ?></span>
          </div>
          <div class="form-group <?php echo (!empty($data_err)) ? 'has-error' : ''; ?>">
              <label class="lbl">Data</label>
              <input type="date" name="data" class="form-control" value="<?php echo $data; ?>">
              <span class="help-block text-danger"><?php echo $data_err; ?></span>
          </div>
          <div class="form-group <?php echo (!empty($descrizione_err)) ? 'has-error' : ''; ?>">
              <label class="lbl">Descrizione</label>
              <input type="text" name="descrizione" class="form-control" value="<?php echo $descrizione; ?>" placeholder="Descrizione">
              <span class="help-block text-danger"><?php echo $descrizione_err; ?></span>
          </div>
          <div class="form-group">
              <input type="submit" class="btn btn-primary" value="Prenota">
              <input type="reset" class="btn btn-secondary" value="Reimposta">
          </div>
      </form>
    </div>
    </div>
  </main>

  <footer class="mastfoot mt-auto">
    <div class="inner">
      <p>© 2019 Studio Legale L. & S.</p>
    </div>
  </footer>
</div>


</body></html>