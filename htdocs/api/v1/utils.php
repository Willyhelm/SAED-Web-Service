<?php
function bad_request()
{
    // codice risposta - 400 Bad request
    http_response_code(400);
    // comunica all'utente
    echo json_encode(array("message" => "Richiesta malformata o illegale."));
    exit(0);
}
?>