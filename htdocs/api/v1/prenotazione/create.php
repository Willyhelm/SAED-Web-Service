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

// ottieni i dati in input da POST
$data = json_decode(file_get_contents("php://input"));

// assicuriamoci che i dati non sono vuoti
if (
    !empty($data->email_cliente) &&
    !empty($data->email_avvocato) &&
    !empty($data->data) &&
    !empty($data->descrizione)
) {

    // imposto i valori della prenotazione
    $prenotazione->email_cliente = $data->email_cliente;
    $prenotazione->email_avvocato = $data->email_avvocato;
    $prenotazione->data = $data->data;
    $prenotazione->descrizione = $data->descrizione;

    // creo la prenotazione
    if ($prenotazione->create()) {

        // codice risposta - 201 created
        http_response_code(201);

        // informo l'utente
        echo json_encode(array("message" => "Prenotazione effettuata."));
    } else { // se non posso creare la prenotazione informo l'utente

        // codice risposta - 503 service unavailable
        http_response_code(503);

        // informo l'utente
        echo json_encode(array("message" => "Errore di connessione."));
    }
} else { // informo l'utente che manca qualche dato

    // codice risposta - 400 bad request
    http_response_code(400);

    // informo l'utente
    echo json_encode(array("message" => "Richiesta malformata o illegale."));
}
?>