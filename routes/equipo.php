<?php

if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_SERVER['REQUEST_URI'] == $routeBase . 'equipo') {
    if (isset($headers['token'])) {
        $dbController = new DbController(Config::$DB['equipo_table']);
        $equipoController = new EquipoController($dbController);

        $resultValidarToken = $dbController->validarToken($headers['token']);

        if ($resultValidarToken->status != 200) {
            Response::sendResponse($resultValidarToken);
            return $found = true;
        }

        $response = $equipoController->getAll();

        Response::sendResponse($response);
        return $found = true;
    }
    Response::sendResponse(new Response(401, 'El token es necesario.'));
    return;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SERVER['REQUEST_URI'] == $routeBase . 'equipo') {
    if (isset($headers['token'])) {
        $dbController = new DbController(Config::$DB['equipo_table']);
        $equipoModel = new Equipo();
        $equipoController = new EquipoController($dbController, $equipoModel);

        $resultValidarToken = $dbController->validarToken($headers['token']);

        if ($resultValidarToken->status != 200) {
            Response::sendResponse($resultValidarToken);
            return $found = true;
        }

        $response = $equipoController->insert($_POST, $headers['token']);

        Response::sendResponse($response);
        return $found = true;
    }
    Response::sendResponse(new Response(401, 'El token es obligatorio.'));
}
if ($_SERVER['REQUEST_METHOD'] == 'PUT' && $_SERVER['REQUEST_URI'] == $routeBase . 'equipo') {
    if (isset($headers['token'])) {
        $dbController = new DbController(Config::$DB['equipo_table']);
        $equipoModel = new Equipo();
        $equipoController = new EquipoController($dbController, $equipoModel);

        $resultValidarToken = $dbController->validarToken($headers['token']);

        if ($resultValidarToken->status != 200) {
            Response::sendResponse($resultValidarToken);
            return $found = true;
        }

        $response = $equipoController->update($_PUT['id'], $_PUT, $headers['token']);

        Response::sendResponse($response);
        return $found = true;
    }
    Response::sendResponse(new Response(401, 'El token es obligatorio.'));
}
// if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
//     $response = $equipoController->delete($routesArray[3]);

//     Response::sendResponse($response);
//     return;
// }



// if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
//     return;
// }
