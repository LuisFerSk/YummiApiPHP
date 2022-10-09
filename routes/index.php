<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header('Content-Type: application/json');

$routeBase = "/YummiApiPHP/";
$found = false;

include "usuario.php";
include "authentication.php";
include "equipo.php";
include "funcionario.php";
include "periferico.php";
include "sectorial.php";
include "subsector.php";

if (!$found) {
    $json = [
        'status' => 404,
        'result' => 'Not found.'
    ];

    echo json_encode($json, http_response_code($json['status']));
}
