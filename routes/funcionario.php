<?php

if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_SERVER['REQUEST_URI'] == $routeBase . 'funcionario') {
    if (isset($headers['token'])) {
        $dbController = new DbController(Config::$DB['funcionario_table']);
        $funcionarioController = new FuncionarioController($dbController);

        $resultValidarToken = $dbController->validarToken($headers['token']);

        if ($resultValidarToken->status != 200) {
            Response::sendResponse($resultValidarToken);
            return $found = true;
        }

        $response = $funcionarioController->getAll();

        Response::sendResponse($response);
        return $found = true;
    }
    Response::sendResponse(new Response(401, 'El token es necesario.'));
    return;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SERVER['REQUEST_URI'] == $routeBase . 'funcionario') {
    if (isset($headers['token'])) {
        $dbController = new DbController(Config::$DB['funcionario_table']);
        $funcionarioModel = new Funcionario();
        $funcionarioController = new FuncionarioController($dbController, $funcionarioModel);

        $resultValidarToken = $dbController->validarToken($headers['token']);

        if ($resultValidarToken->status != 200) {
            Response::sendResponse($resultValidarToken);
            return $found = true;
        }

        $response = $funcionarioController->insert($_POST, $headers['token']);

        Response::sendResponse($response);
        return $found = true;
    }
    Response::sendResponse(new Response(401, 'El token es obligatorio.'));
}
if ($_SERVER['REQUEST_METHOD'] == 'PUT' && $_SERVER['REQUEST_URI'] == $routeBase . 'funcionario') {
    if (isset($headers['token'])) {
        $dbController = new DbController(Config::$DB['funcionario_table']);
        $funcionarioModel = new Funcionario();
        $funcionarioController = new FuncionarioController($dbController, $funcionarioModel);

        $resultValidarToken = $dbController->validarToken($headers['token']);

        if ($resultValidarToken->status != 200) {
            Response::sendResponse($resultValidarToken);
            return $found = true;
        }

        $response = $funcionarioController->update($_PUT['id'], $_PUT, $headers['token']);

        Response::sendResponse($response);
        return $found = true;
    }
    Response::sendResponse(new Response(401, 'El token es obligatorio.'));
}
if ($_SERVER['REQUEST_METHOD'] == 'DELETE' && $_SERVER['REQUEST_URI'] == $routeBase . 'funcionario') {
    if (isset($headers['token'])) {
        $dbController = new DbController(Config::$DB['funcionario_table']);
        $Funcionario = new Funcionario();
        $funcionarioController = new FuncionarioController($dbController, $Funcionario);

        $resultValidarToken = $dbController->validarToken($headers['token']);

        if ($resultValidarToken->status != 200) {
            Response::sendResponse($resultValidarToken);
            return $found = true;
        }
        if ($resultValidarToken->data->rol != Config::$ROLES['ADMINISTRADOR']['id']) {
            Response::sendResponse(new Response(403, 'Usted no tiene permisos para realizar esta acción.'));
            return $found = true;
        }

        $response = $funcionarioController->delete($_PUT['id'], $headers['token']);

        Response::sendResponse($response);
        return $found = true;
    }
    Response::sendResponse(new Response(401, 'El token es necesario.'));
    return $found = true;
}
if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_SERVER['REQUEST_URI'] == $routeBase . 'funcionario/count') {
    if (isset($headers['token'])) {
        $dbController = new DbController(Config::$DB['funcionario_table']);
        $funcionarioController = new FuncionarioController($dbController);

        $resultValidarToken = $dbController->validarToken($headers['token']);

        if ($resultValidarToken->status != 200) {
            Response::sendResponse($resultValidarToken);
            return $found = true;
        }

        $response = $funcionarioController->count();

        Response::sendResponse($response);
        return $found = true;
    }
    Response::sendResponse(new Response(401, 'El token es necesario.'));
    return;
}
