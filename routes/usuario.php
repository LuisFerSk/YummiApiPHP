<?php

if ($_SERVER['REQUEST_URI'] == $routeBase . 'usuario/restore_admin') {
    $dbController = new DbController(Config::$DB['usuario_table']);
    $usuarioModel = new Usuario();
    $usuarioController = new UsuarioController($dbController, $usuarioModel);

    $response = $usuarioController->insertAdmin();
    Response::sendResponse($response);

    return $found = true;
}
if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_SERVER['REQUEST_URI'] == $routeBase . 'usuario') {
    if (isset($headers['token'])) {
        $dbController = new DbController(Config::$DB['usuario_table']);
        $usuarioController = new UsuarioController($dbController);

        try {
            $decode = DbController::decode($headers['token']);
        } catch (Exception $exception) {
            Response::sendResponse(new Response(400, 'Ha sucedido un error al validar el token.', $exception));
            return $found = true;
        }

        if ($decode->exp > time()) {
            $response = $usuarioController->getAll();

            Response::sendResponse($response);
            return $found = true;
        }
        Response::sendResponse(new Response(400, 'El token ya expiro.'));
        return $found = true;
    }
    Response::sendResponse(new Response(400, 'El token es necesario.'));
    return $found = true;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SERVER['REQUEST_URI'] == $routeBase . 'usuario') {
    if (isset($headers['token'])) {
        $dbController = new DbController(Config::$DB['usuario_table']);
        $usuarioModel = new Usuario();
        $usuarioController = new UsuarioController($dbController, $usuarioModel);

        try {
            $decode = DbController::decode($headers['token']);
        } catch (Exception $exception) {
            Response::sendResponse(new Response(400, 'Ha sucedido un error al validar el token.', $exception));
            return $found = true;
        }

        if ($decode->exp > time()) {
            $response = $usuarioController->insert($_POST);

            Response::sendResponse($response);
            return $found = true;
        }
        Response::sendResponse(new Response(400, 'El token ya expiro.'));
        return $found = true;
    }
    Response::sendResponse(new Response(400, 'El token es necesario.'));
    return $found = true;
}
