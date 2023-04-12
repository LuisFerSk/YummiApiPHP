<?php

header('Access-Control-Allow-Origin: https://itecnologico.valledupar.gov.co');
header("Access-Control-Allow-Methods: HEAD, GET, POST, PUT, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: id, token, X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization");
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
if ($method == "OPTIONS") {
    header('Access-Control-Allow-Origin: https://itecnologico.valledupar.gov.co');
    header("Access-Control-Allow-Headers: id, token, X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization");
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
include_once 'controllers/log.controller.php';

include_once 'models/response.model.php';
include_once 'models/usuario.model.php';
include_once 'models/equipo.model.php';
include_once 'models/funcionario.model.php';
include_once 'models/periferico.model.php';

include_once 'utils/excel.php';

ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', './php_error_log');

$headers = getallheaders();

parse_str(file_get_contents("php://input"), $_PUT);

include "routes/index.php";
