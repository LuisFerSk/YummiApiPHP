<?php

if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_SERVER['REQUEST_URI'] == $routeBase . 'periferico') {
    if (isset($headers['token'])) {
        $dbController = new DbController(Config::$DB['periferico_table']);
        $perifericoController = new PerifericoController($dbController);

        $resultValidarToken = $dbController->validarToken($headers['token']);

        if ($resultValidarToken->status != 200) {
            Response::sendResponse($resultValidarToken);
            return $found = true;
        }

        $response = $perifericoController->getAll();

        Response::sendResponse($response);
        return $found = true;
    }
    Response::sendResponse(new Response(401, 'El token es necesario.'));
    return;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SERVER['REQUEST_URI'] == $routeBase . 'periferico') {
    if (isset($headers['token'])) {
        $dbController = new DbController(Config::$DB['periferico_table']);
        $perifericoModel = new Periferico();
        $perifericoController = new PerifericoController($dbController, $perifericoModel);

        $resultValidarToken = $dbController->validarToken($headers['token']);

        if ($resultValidarToken->status != 200) {
            Response::sendResponse($resultValidarToken);
            return $found = true;
        }

        $response = $perifericoController->insert($_POST, $headers['token']);

        Response::sendResponse($response);
        return $found = true;
    }
    Response::sendResponse(new Response(401, 'El token es obligatorio.'));
}
if ($_SERVER['REQUEST_METHOD'] == 'PUT' && $_SERVER['REQUEST_URI'] == $routeBase . 'periferico') {
    if (isset($headers['token'])) {
        $dbController = new DbController(Config::$DB['periferico_table']);
        $perifericoModel = new Periferico();
        $perifericoController = new PerifericoController($dbController, $perifericoModel);

        $resultValidarToken = $dbController->validarToken($headers['token']);

        if ($resultValidarToken->status != 200) {
            Response::sendResponse($resultValidarToken);
            return $found = true;
        }

        $response = $perifericoController->update($_PUT['id'], $_PUT, $headers['token']);

        Response::sendResponse($response);
        return $found = true;
    }
    Response::sendResponse(new Response(401, 'El token es obligatorio.'));
}
if ($_SERVER['REQUEST_METHOD'] == 'DELETE' && $_SERVER['REQUEST_URI'] == $routeBase . 'periferico') {
    if (isset($headers['token'])) {
        $dbController = new DbController(Config::$DB['periferico_table']);
        $Periferico = new Periferico();
        $perifericoController = new PerifericoController($dbController, $Periferico);

        $resultValidarToken = $dbController->validarToken($headers['token']);

        if ($resultValidarToken->status != 200) {
            Response::sendResponse($resultValidarToken);
            return $found = true;
        }
        if ($resultValidarToken->data->rol != Config::$ROLES['ADMINISTRADOR']['id']) {
            Response::sendResponse(new Response(403, 'Usted no tiene permisos para realizar esta acción.'));
            return $found = true;
        }

        $response = $perifericoController->delete($_PUT['id'], $headers['token']);

        Response::sendResponse($response);
        return $found = true;
    }
    Response::sendResponse(new Response(401, 'El token es necesario.'));
    return $found = true;
}
if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_SERVER['REQUEST_URI'] == $routeBase . 'periferico/count') {
    if (isset($headers['token'])) {
        $dbController = new DbController(Config::$DB['periferico_table']);
        $perifericoController = new PerifericoController($dbController);

        $resultValidarToken = $dbController->validarToken($headers['token']);

        if ($resultValidarToken->status != 200) {
            Response::sendResponse($resultValidarToken);
            return $found = true;
        }

        $response = $perifericoController->count();

        Response::sendResponse($response);
        return $found = true;
    }
    Response::sendResponse(new Response(401, 'El token es necesario.'));
    return;
}