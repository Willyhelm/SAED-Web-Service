<?php
function session_exists($session)
{
    return (isset($session) && !empty($session) ? true : false);
}

function curl_get($url)
{
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $url
    ]);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

function curl_post($url, $array_data)
{
    $additional_headers = array(
        'Accept: application/json',
        'Content-Type: application/json'
    );
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($array_data),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $additional_headers
    ]);
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response);
}
?>