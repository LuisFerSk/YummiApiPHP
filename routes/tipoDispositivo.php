<?php

if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_SERVER['REQUEST_URI'] == $routeBase . 'tipo-dispositivo') {
    if (isset($headers['Token'])) {
        $dbController = new DbController(Config::$DB['tipo_dispositivo_table']);
        $tipoDispositivoController = new TipoDispositivoController($dbController);

        $resultValidarToken = $dbController->validarToken($headers['Token']);

        if ($resultValidarToken->status != 200) {
            Response::sendResponse($resultValidarToken);
            return $found = true;
        }

        $response = $tipoDispositivoController->getAll();

        Response::sendResponse($response);
        return $found = true;
    }
    Response::sendResponse(new Response(401, 'El token es necesario.'));
    return $found = true;
}
