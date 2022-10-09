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
    return $found = true;
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
    return $found = true;
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
if ($_SERVER['REQUEST_METHOD'] == 'DELETE' && $_SERVER['REQUEST_URI'] == $routeBase . 'equipo') {
    if (isset($headers['token'])) {
        $dbController = new DbController(Config::$DB['equipo_table']);
        $equipoModel = new Equipo();
        $equipoController = new EquipoController($dbController, $equipoModel);

        $resultValidarToken = $dbController->validarToken($headers['token']);

        if ($resultValidarToken->status != 200) {
            Response::sendResponse($resultValidarToken);
            return $found = true;
        }
        if ($resultValidarToken->data->rol != Config::$ROLES['ADMINISTRADOR']['id']) {
            Response::sendResponse(new Response(403, 'Usted no tiene permisos para realizar esta acción.'));
            return $found = true;
        }

        $response = $equipoController->delete($_PUT['id'], $headers['token']);

        Response::sendResponse($response);
        return $found = true;
    }
    Response::sendResponse(new Response(401, 'El token es necesario.'));
    return $found = true;
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_SERVER['REQUEST_URI'] == $routeBase . 'equipo/count') {
    if (isset($headers['token'])) {
        $dbController = new DbController(Config::$DB['equipo_table']);
        $equipoController = new EquipoController($dbController);

        $resultValidarToken = $dbController->validarToken($headers['token']);

        if ($resultValidarToken->status != 200) {
            Response::sendResponse($resultValidarToken);
            return $found = true;
        }

        $response = $equipoController->count();

        Response::sendResponse($response);
        return $found = true;
    }
    Response::sendResponse(new Response(401, 'El token es necesario.'));
    return;
}
