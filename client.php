<?php

$ch = curl_init( $argv[1] );
curl_setopt(
    $ch,
    CURLOPT_RETURNTRANSFER,
    true
);

$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

switch ($httpcode) {
    case 200:
        echo "OK";
        break;
    case 400:
        echo "INCORRECTO";
        break;
    case 500:
        echo "ERROR DE SERVIDOR";
        break;
    default: 
        echo "OTRO ERROR" . $httpcode;
        break;
}