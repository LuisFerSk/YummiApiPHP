<?php

if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_SERVER['REQUEST_URI'] == $routeBase . 'subsector') {
    if (isset($headers['token'])) {
        $dbController = new DbController(Config::$DB['subsector_table']);
        $subsectorController = new SubsectorController($dbController);

        $resultValidarToken = $dbController->validarToken($headers['token']);

        if ($resultValidarToken->status != 200) {
            Response::sendResponse($resultValidarToken);
            return $found = true;
        }

        $response = $subsectorController->getAll();

        Response::sendResponse($response);
        return $found = true;
    }
    Response::sendResponse(new Response(401, 'El token es necesario.'));
    return;
}