<?php
// header necessari
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../utils.php';

if ($_SERVER['REQUEST_METHOD'] != 'GET') {
    bad_request();
}

// includo file al database ed all'oggetto
include_once '../../../../database.php';
include_once '../objects/prenotazione.php';

// istanzio il database
$database = new Database();
$db = $database->getConnection();

// istanzio l'oggetto
$prenotazione = new Prenotazione($db);

// leggo in base al parametro GET
if (isset($_GET['email_cliente'])) {
    if (empty($_GET['email_cliente'])) {
        bad_request();
    }
    $stmt = $prenotazione->read_email_cliente($_GET['email_cliente']);
} elseif (isset($_GET['email_avvocato'])) {
    if (empty($_GET['email_avvocato'])) {
        bad_request();
    }
    $stmt = $prenotazione->read_email_avvocato($_GET['email_avvocato']);
} elseif (isset($_GET['id'])) {
    if (empty($_GET['id'])) {
        bad_request();
    }
    $stmt = $prenotazione->read_id($_GET['id']);
} elseif (strpos($_SERVER['REQUEST_URI'], '?') == false) {
    $stmt = $prenotazione->read();
} else {
    bad_request();
}

$num = $stmt->rowCount();

// controlla se i record trovati sono >= 1
if ($num > 0) {

    // array prenotazioni
    $prenotazione_arr=array();
    $prenotazione_arr["results"]=array();

    // ottiene il contenuto della tabella
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // estrae la riga nella variabile omonima
        extract($row);

        $prenotazione_item=array(
            "id" => $id,
            "email_cliente" => $email_cliente,
            "email_avvocato" => $email_avvocato,
            "data" => $data,
            "descrizione" => $descrizione
        );

        array_push($prenotazione_arr["results"], $prenotazione_item);
    }

    // codice risposta - 200 OK
    http_response_code(200);

    // mostra le prenotazioni in formato JSON
    echo json_encode($prenotazione_arr);
} else { // prenotazioni non trovate
    // codice risposta - 404 Not found
    http_response_code(404);
    // comunica all'utente
    echo json_encode(array("message" => "Nessuna prenotazione"));
}
?>