<?php

$dbController = new DbController(Config::$DB['usuario_table']);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SERVER['REQUEST_URI'] == $routeBase . 'authentication') {
    $usuarioModel = new Usuario();
    $usuarioController = new UsuarioController($dbController, $usuarioModel);

    $response = $usuarioController->login($_POST);

    Response::sendResponse($response);
    return $found = true;
}
