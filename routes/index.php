<?php

$routeBase = "/YummiApiPHP/";
$found = false;

include "usuario.php";
include "authentication.php";
include "equipo.php";
include "funcionario.php";
include "periferico.php";
include "sectorial.php";
include "subsector.php";
include "tipoDispositivo.php";
include "log.php";

if (!$found) {
    $json = [
        'status' => 404,
        'result' => 'Not found.'
    ];

    echo json_encode($json, http_response_code($json['status']));
}
