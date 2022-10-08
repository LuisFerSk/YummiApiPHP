<?php

include_once 'controllers/equipo.controller.php';
include_once 'models/equipo.model.php';

$dbController = new DbController(Config::$DB['equipo_table']);
$equipoModel = new Equipo();
$equipoController = new EquipoController($dbController, $equipoModel);

$headers = getallheaders();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($headers['token'])) {
        try {
            DbController::decode($headers['token']);

            $response = $equipoController->getAll();

            Response::sendResponse($response);
            return;
        } catch (Exception $exception) {
            Response::sendResponse(new Response(400, 'Ha sucedido un error al validar el token.'));
            return;
        }
    }
    Response::sendResponse(new Response(400, 'El token es necesario.'));
    return;
}

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $response = $equipoController->delete($routesArray[3]);

    Response::sendResponse($response);
    return;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($headers['token'])) {
        try {
            $response = $equipoController->insert($_POST, $headers['token']);

            Response::sendResponse($response);
            return;
        } catch (Exception $exception) {
            Response::sendResponse(new Response(400, 'Ha sucedido un error al validar el token.'));
            return;
        }
    }
    Response::sendResponse(new Response(400, 'El token es obligatorio.'));
}

if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    return;
}
