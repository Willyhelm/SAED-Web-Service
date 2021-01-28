<?php
session_start();
include_once 'utils.php';
if (session_exists($_SESSION)) {
    if($_SESSION['tipo'] == 'cliente') {
        header('Location: profilo.php', true, 307);
    } else {
        header('Location: profiloavvocato.php', true, 307);
    }
    exit(0);
}
// Include file configurazione database
require_once "../database.php";

// Inizializza le variabili con stringhe vuote
$nome = $cognome = $email = $password = $confirm_password = "";
$nome_err = $cognome_err = $email_err = $password_err = $confirm_password_err = "";

// Elabora i dati della form se essa è stata inviata
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Valida nome
    if (empty(trim($_POST["nome"]))) {
        $nome_err = "Inserisci il nome.";
    }

    // Valida cognome
    if (empty(trim($_POST["cognome"]))) {
        $cognome_err = "Inserisci il cognome.";
    }

    // Valida email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Inserisci un'e-mail.";
    } else {
        // Prepara la select
        $sql = "SELECT `email` FROM `cliente` WHERE `email` = :email";

        // istanzio il database
        $database = new Database();
        $db = $database->getConnection();

        if ($stmt = $db->prepare($sql)) {
            // Assegna le variabili ai parametri del prepared statement
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);

            // Imposta i parametri
            $param_email = trim($_POST["email"]);

            // Prova ad eseguire il prepared statement
            if ($stmt->execute()) {
                if ($stmt->rowCount() == 1) {
                    $email_err = "Scegli un'altra e-mail.";
                } else {
                    $email = trim($_POST["email"]);
                }
            } else {
                echo "Oops! Qualcosa è andato storto. Riprova più tardi.";
            }
        }

        // Chiude statement
        unset($stmt);
    }

    // Valida password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Inserisci la password.";
    } elseif (strlen(trim($_POST["password"])) < 8) {
        $password_err = "La password deve avere almeno 8 caratteri.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Valida conferma password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Conferma la password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Le password non corrispondono.";
        }
    }

    // Controlla che non ci sono errori prima di inserire nel database
    if (empty($nome_err) && empty($cognome_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)) {

        // Prepare l'insert
        $sql = "INSERT INTO `cliente` VALUES (:nome, :cognome, :email, :password)";

        if ($stmt = $db->prepare($sql)) {

            // Imposta i parametri
            $param_nome = $_POST['nome'];
            $param_cognome = $_POST['cognome'];
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_ARGON2ID); // Fa l'hash della password

            // Assegna variabili ai parametri del prepared statement
            $stmt->bindParam(":nome", $param_nome, PDO::PARAM_STR);
            $stmt->bindParam(":cognome", $param_cognome, PDO::PARAM_STR);
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
            $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);

            // Prova a fare il prepared statement
            if ($stmt->execute()) {
                // Reindirizza al login
                header('Location: login.php', true, 302);
                exit(0);
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
    <title>Registrazione - Studio Legale L. & S.</title>
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
        <p class="nav-link active">Registrazione</p>
      </nav>
    </div>
  </header>

  <main role="main" class="inner cover">
  <div class="wrapper">
        <h2>Crea account</h2>
        <p>Completa tutti i campi per creare l'account.<br>In questo modo, potrai prenotare consulenze presso lo Studio Legale L. & S..</p>
        <div class="frm">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group <?php echo (!empty($nome_err)) ? 'has-error' : ''; ?>">
                    <label class="lbl">Nome</label>
                    <input type="text" name="nome" class="form-control" value="<?php echo $nome; ?>" placeholder="Nome">
                    <span class="help-block text-danger"><?php echo $nome_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($cognome_err)) ? 'has-error' : ''; ?>">
                    <label class="lbl">Cognome</label>
                    <input type="text" name="cognome" class="form-control" value="<?php echo $cognome; ?>" placeholder="Cognome">
                    <span class="help-block text-danger"><?php echo $cognome_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                    <label class="lbl">E-mail</label>
                    <input type="text" name="email" class="form-control" value="<?php echo $email; ?>" placeholder="E-mail">
                    <span class="help-block text-danger"><?php echo $email_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                    <label class="lbl">Password</label>
                    <input type="password" name="password" class="form-control" value="<?php echo $password; ?>" placeholder="Password">
                    <span class="help-block text-danger"><?php echo $password_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                    <label class="lbl">Conferma password</label>
                    <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>" placeholder="Conferma password">
                    <span class="help block text-danger"><?php echo $confirm_password_err; ?></span>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Crea">
                    <input type="reset" class="btn btn-secondary" value="Reimposta">
                </div>
                <div>Hai già un account?<a href="login.php" class="btn btn-link">Accedi</a></div>
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