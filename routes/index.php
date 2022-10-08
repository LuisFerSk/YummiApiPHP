<?php

$routeBase = "/YummiApiPHP/";
$found = false;
$headers = getallheaders();

include "usuario.php";
include "authentication.php";

if (!$found) {
    $json = [
        'status' => 404,
        'result' => 'Not found.'
    ];

    echo json_encode($json, http_response_code($json['status']));
}
