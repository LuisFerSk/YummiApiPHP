<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: HEAD, GET, POST, PUT, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: token, X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization");
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
if ($method == "OPTIONS") {
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: token, X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization");
    header("HTTP/1.1 200 OK");
    die();
}

include_once 'config/index.php';

include_once 'controllers/db.controller.php';
include_once 'controllers/usuario.controller.php';
include_once 'controllers/equipo.controller.php';
include_once 'controllers/funcionario.controller.php';
include_once 'controllers/periferico.controller.php';
include_once 'controllers/sectorial.controller.php';
include_once 'controllers/subsector.controller.php';
include_once 'controllers/tipoDispositivo.controller.php';

include_once 'models/response.model.php';
include_once 'models/usuario.model.php';
include_once 'models/equipo.model.php';
include_once 'models/funcionario.model.php';
include_once 'models/periferico.model.php';

ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', './php_error_log');

if (!function_exists('getallheaders')) {
    function getallheaders()
    {
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}

$headers = getallheaders();

parse_str(file_get_contents("php://input"), $_PUT);

include "routes/index.php";
