<?php

if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_SERVER['REQUEST_URI'] == $routeBase . 'funcionario') {
    if (isset($headers['token'])) {
        $dbController = new DbController(Config::$DB['funcionario_table']);
        $functionarioController = new FuncionarioController($dbController);

        $resultValidarToken = $dbController->validarToken($headers['token']);

        if ($resultValidarToken->status != 200) {
            Response::sendResponse($resultValidarToken);
            return $found = true;
        }

        $response = $functionarioController->getAll();

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
        $functionarioController = new FuncionarioController($dbController, $funcionarioModel);

        $resultValidarToken = $dbController->validarToken($headers['token']);

        if ($resultValidarToken->status != 200) {
            Response::sendResponse($resultValidarToken);
            return $found = true;
        }

        $response = $functionarioController->insert($_POST, $headers['token']);

        Response::sendResponse($response);
        return $found = true;
    }
    Response::sendResponse(new Response(401, 'El token es obligatorio.'));
}
