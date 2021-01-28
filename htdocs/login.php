<?php
// Inizializza la sessione
session_start();

// Se l'utente è già loggato, reindirizzalo alla pagina profilo appropriata
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    if ($_SESSION['tipo'] == 'cliente') {
        header('Location: profilo.php', true, 307);
    } else {
        header('Location: profiloavvocato.php', true, 307);
    }
    exit(0);
}

// File di configurazione del database
require_once "../database.php";

// Inizializza le variabili a stringa vuota
$email = $password = "";
$email_err = $password_err = "";

// Elabora i dati della form se essa è stata inviata
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Controlla se l'email è vuota
    if (empty(trim($_POST["email"]))) {
        $email_err = "Inserisci l'e-mail.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Controlla se la password è vuota
    if (empty(trim($_POST["password"]))) {
        $password_err = "Inserisci la password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Valida credenziali
    if (empty($email_err) && empty($password_err)) {
        // Prepara la select
        $sql = "SELECT `nome`, `cognome`, `email`, `password` FROM `cliente` WHERE `email` = :email";

        // istanzio il database
        $database = new Database();
        $db = $database->getConnection();

        if ($stmt = $db->prepare($sql)) {
            // Assegna le variabili ai parametri del prepared statement
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);

            // Imposta i parametri
            $param_email = strtolower(trim($_POST["email"]));

            // Prova ad eseguire la query
            if ($stmt->execute()) {
                // Controlla l'email, poi la password
                if ($stmt->rowCount() == 1) {
                    if ($row = $stmt->fetch()) {
                        $nome = $row['nome'];
                        $cognome = $row['cognome'];
                        $email = $row["email"];
                        $hashed_password = $row["password"];
                        if (password_verify($password, $hashed_password)) {
                            // Password verificata quindi inizia la sessione
                            session_start();

                            // Salva le variabili di sessione
                            $_SESSION["loggedin"] = true;
                            $_SESSION["email"] = $email;
                            $_SESSION['nome'] = $nome;
                            $_SESSION['cognome'] = $cognome;
                            $_SESSION['tipo'] = 'cliente';

                            // Reindirizza al profilo
                            header('Location: profilo.php', true, 302);
                            exit(0);
                        } else {
                            // Se la password non è valida mostra l'errore
                            $password_err = "La password inserita non è valida.";
                        }
                    }
                } else {
                    // Se l'email non è valida mostra l'errore
                    $email_err = "L'e-mail non è valida.";
                }
            } else {
                echo "Oops! Qualcosa è andato storto. Riprova più tardi.";
            }
        }

        // Chiude statement
        unset($stmt);
    }

    // Chiude connessione
    unset($db);
}
?>

<!DOCTYPE html>
<html lang="it">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Accesso clienti - Studio Legale L. & S.</title>
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
        <p class="nav-link active">Accesso clienti</p>
      </nav>
    </div>
  </header>

  <main role="main" class="inner cover">
  <div class="wrapper">
        <h2>Accedi come cliente</h2>
        <p>Benvenuto, cliente.<br>Inserisci le credenziali d'accesso.</p>
        <div class="frm">
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
              <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                  <label class="lbl">E-mail</label>
                  <input type="text" name="email" class="form-control" value="<?php echo $email; ?>" placeholder="E-mail">
                  <span class="help block text-danger"><?php echo $email_err; ?></span>
              </div>
              <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                  <label class="lbl">Password</label>
                  <input type="password" name="password" class="form-control" placeholder="Password">
                  <span class="help block text-danger"><?php echo $password_err; ?></span>
              </div>
              <div class="form-group">
                  <input type="submit" class="btn btn-primary" value="Accedi">
              </div>
              <div>Non hai un account?<a href="register.php" class="btn btn-link">Registrati</a></div>
              <div>Sei un avvocato?<a href="loginavvocato.php" class="btn btn-link">Accedi qui</a></div>
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