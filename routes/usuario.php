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

        $resultValidarToken = $dbController->validarToken($headers['token']);

        if ($resultValidarToken->status != 200) {
            Response::sendResponse($resultValidarToken);
            return $found = true;
        }

        if ($resultValidarToken->data->rol != Config::$ROLES['ADMINISTRADOR']['id']) {
            Response::sendResponse(new Response(403, 'Usted no tiene permisos para realizar esta acción.'));
            return $found = true;
        }

        $response = $usuarioController->getAll();

        Response::sendResponse($response);
        return $found = true;
    }
    Response::sendResponse(new Response(401, 'El token es necesario.'));
    return $found = true;
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_SERVER['REQUEST_URI'] == $routeBase . 'usuario/me') {
    if (isset($headers['token'])) {
        $dbController = new DbController(Config::$DB['usuario_table']);
        $usuarioController = new UsuarioController($dbController);

        $result = $dbController->validarToken($headers['token']);

        if ($result->status != 200) {
            Response::sendResponse($result);
            return $found = true;
        }

        $message = 'Se ha obtenido la información de su usuario correctamente.';
        Response::sendResponse(new Response($result->status, $message, $result->data));
        return $found = true;
    }
    Response::sendResponse(new Response(401, 'El token es necesario.'));
    return $found = true;
}

if ($_SERVER['REQUEST_METHOD'] == 'PUT' && $_SERVER['REQUEST_URI'] == $routeBase . 'usuario/me/password') {
    if (isset($headers['token'])) {
        $dbController = new DbController(Config::$DB['usuario_table']);
        $usuarioModel = new Usuario();
        $usuarioController = new UsuarioController($dbController, $usuarioModel);

        $resultValidarToken = $dbController->validarToken($headers['token']);

        if ($resultValidarToken->status != 200) {
            Response::sendResponse($resultValidarToken);
            return $found = true;
        }

        $response = $usuarioController->updatePassword($resultValidarToken->data->id, $_PUT['password']);

        Response::sendResponse($response);
        return $found = true;
    }
    Response::sendResponse(new Response(401, 'El token es necesario.'));
    return $found = true;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SERVER['REQUEST_URI'] == $routeBase . 'usuario') {
    if (isset($headers['token'])) {
        $dbController = new DbController(Config::$DB['usuario_table']);
        $usuarioModel = new Usuario();
        $usuarioController = new UsuarioController($dbController, $usuarioModel);

        $resultValidarToken = $dbController->validarToken($headers['token']);

        if ($resultValidarToken->status != 200) {
            Response::sendResponse($resultValidarToken);
            return $found = true;
        }

        if ($resultValidarToken->data->rol != Config::$ROLES['ADMINISTRADOR']['id']) {
            Response::sendResponse(new Response(403, 'Usted no tiene permisos para realizar esta acción.'));
            return $found = true;
        }

        $response = $usuarioController->insert($_POST);

        Response::sendResponse($response);
        return $found = true;
    }
    Response::sendResponse(new Response(401, 'El token es necesario.'));
    return $found = true;
}
if ($_SERVER['REQUEST_METHOD'] == 'PUT' && $_SERVER['REQUEST_URI'] == $routeBase . 'usuario') {
    if (isset($headers['token'])) {
        $dbController = new DbController(Config::$DB['usuario_table']);
        $usuarioModel = new Usuario();
        $usuarioController = new UsuarioController($dbController, $usuarioModel);

        $resultValidarToken = $dbController->validarToken($headers['token']);

        if ($resultValidarToken->status != 200) {
            Response::sendResponse($resultValidarToken);
            return $found = true;
        }

        if ($resultValidarToken->data->rol != Config::$ROLES['ADMINISTRADOR']['id']) {
            Response::sendResponse(new Response(403, 'Usted no tiene permisos para realizar esta acción.'));
            return $found = true;
        }

        $response = $usuarioController->updatePassword($_PUT['id'], $_PUT['password']);

        Response::sendResponse($response);
        return $found = true;
    }
    Response::sendResponse(new Response(401, 'El token es necesario.'));
    return $found = true;
}

if ($_SERVER['REQUEST_METHOD'] == 'DELETE' && $_SERVER['REQUEST_URI'] == $routeBase . 'usuario') {
    if (isset($headers['token'])) {
        $dbController = new DbController(Config::$DB['usuario_table']);
        $usuarioModel = new Usuario();
        $usuarioController = new UsuarioController($dbController, $usuarioModel);

        $resultValidarToken = $dbController->validarToken($headers['token']);

        if ($resultValidarToken->status != 200) {
            Response::sendResponse($resultValidarToken);
            return $found = true;
        }
        if ($resultValidarToken->data->rol != Config::$ROLES['ADMINISTRADOR']['id']) {
            Response::sendResponse(new Response(403, 'Usted no tiene permisos para realizar esta acción.'));
            return $found = true;
        }

        $response = $usuarioController->delete($_PUT['id']);

        Response::sendResponse($response);
        return $found = true;
    }
    Response::sendResponse(new Response(401, 'El token es necesario.'));
    return $found = true;
}

if ($_SERVER['REQUEST_METHOD'] == 'PUT' && $_SERVER['REQUEST_URI'] == $routeBase . 'usuario/disable') {
    if (isset($headers['token'])) {
        $dbController = new DbController(Config::$DB['usuario_table']);
        $usuarioModel = new Usuario();
        $usuarioController = new UsuarioController($dbController, $usuarioModel);

        $resultValidarToken = $dbController->validarToken($headers['token']);

        if ($resultValidarToken->status != 200) {
            Response::sendResponse($resultValidarToken);
            return $found = true;
        }

        if ($resultValidarToken->data->rol == Config::$ROLES['ADMINISTRADOR']['id']) {
            Response::sendResponse(new Response(403, 'Usted no tiene permisos para realizar esta acción.'));
            return $found = true;
        }

        $response = $usuarioController->disable($_PUT['id']);

        Response::sendResponse($response);
        return $found = true;
    }
    Response::sendResponse(new Response(401, 'El token es necesario.'));
    return $found = true;
}

if ($_SERVER['REQUEST_METHOD'] == 'PUT' && $_SERVER['REQUEST_URI'] == $routeBase . 'usuario/enable') {
    if (isset($headers['token'])) {
        $dbController = new DbController(Config::$DB['usuario_table']);
        $usuarioModel = new Usuario();
        $usuarioController = new UsuarioController($dbController, $usuarioModel);

        $resultValidarToken = $dbController->validarToken($headers['token']);

        if ($resultValidarToken->status != 200) {
            Response::sendResponse($resultValidarToken);
            return $found = true;
        }

        if ($resultValidarToken->data->rol != Config::$ROLES['ADMINISTRADOR']['id']) {
            Response::sendResponse(new Response(403, 'Usted no tiene permisos para realizar esta acción.'));
            return $found = true;
        }

        $response = $usuarioController->enable($_PUT['id']);

        Response::sendResponse($response);
        return $found = true;
    }
    Response::sendResponse(new Response(401, 'El token es necesario.'));
    return $found = true;
}
