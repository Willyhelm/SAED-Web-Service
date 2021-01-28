<?php
// header necessari
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../utils.php';

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
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

// ottieni l'id della prenotazione
$json = json_decode(file_get_contents('php://input'));
$id = $json->id;

// cancella la prenotazione
if ($prenotazione->delete($id)) {

    // codice risposta - 200 OK
    http_response_code(200);

    // informa l'utente
    echo json_encode(array("message" => "Prenotazione cancellata."));
} else { // se non è possibile cancellare la prenotazione

    // codice risposta - 503 service unavailable
    http_response_code(503);

    // informa l'utente
    echo json_encode(array("message" => "Errore di connessione."));
}
?>