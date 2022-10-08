<?php

include_once 'models/funcionario.model.php';
include_once 'models/response.model.php';
include_once 'controllers/db.controller.php';
include_once 'controllers/funcionario.controller.php';
include_once 'controllers/log.controller.php';
include_once 'config/index.php';

$dbController = new DbController(Config::$DB['FUNCIONARIO_TABLE']);
$logTable = new DbController(Config::$DB['LOG_TABLE']);
$funcionarioModel = new Funcionario();
$functionarioController = new FuncionarioController($dbController, $funcionarioModel);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $response = $functionarioController->getAll();

    Response::sendResponse($response);
    return;
}

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    return;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    return;
}

if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    return;
}
