<?php

if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_SERVER['REQUEST_URI'] == $routeBase . 'periferico') {
    if (isset($headers['Token'])) {
        $dbController = new DbController(Config::$DB['periferico_table']);
        $perifericoController = new PerifericoController($dbController);

        $resultValidarToken = $dbController->validarToken($headers['Token']);

        if ($resultValidarToken->status != 200) {
            Response::sendResponse($resultValidarToken);
            return $found = true;
        }

        $response = $perifericoController->getAll();
        
        Response::sendResponse($response);

        return $found = true;
    }
    Response::sendResponse(new Response(401, 'El token es necesario.'));
    return $found = true;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SERVER['REQUEST_URI'] == $routeBase . 'periferico') {
    if (isset($headers['Token'])) {
        $dbController = new DbController(Config::$DB['periferico_table']);
        $perifericoModel = new Periferico();
        $perifericoController = new PerifericoController($dbController, $perifericoModel);

        $resultValidarToken = $dbController->validarToken($headers['Token']);

        if ($resultValidarToken->status != 200) {
            Response::sendResponse($resultValidarToken);
            return $found = true;
        }

        $response = $perifericoController->insert($_POST, $headers['Token']);

        Response::sendResponse($response);
        return $found = true;
    }
    Response::sendResponse(new Response(401, 'El token es obligatorio.'));
    return $found = true;
}
if ($_SERVER['REQUEST_METHOD'] == 'PUT' && $_SERVER['REQUEST_URI'] == $routeBase . 'periferico') {
    if (isset($headers['Token'])) {
        $dbController = new DbController(Config::$DB['periferico_table']);
        $perifericoModel = new Periferico();
        $perifericoController = new PerifericoController($dbController, $perifericoModel);

        $resultValidarToken = $dbController->validarToken($headers['Token']);

        if ($resultValidarToken->status != 200) {
            Response::sendResponse($resultValidarToken);
            return $found = true;
        }

        $response = $perifericoController->update($_PUT['id'], $_PUT, $headers['Token']);

        Response::sendResponse($response);
        return $found = true;
    }
    Response::sendResponse(new Response(401, 'El token es obligatorio.'));
    return $found = true;
}
if ($_SERVER['REQUEST_METHOD'] == 'DELETE' && $_SERVER['REQUEST_URI'] == $routeBase . 'periferico') {
    if (isset($headers['Token'])) {
        $dbController = new DbController(Config::$DB['periferico_table']);
        $Periferico = new Periferico();
        $perifericoController = new PerifericoController($dbController, $Periferico);

        $resultValidarToken = $dbController->validarToken($headers['Token']);

        if ($resultValidarToken->status != 200) {
            Response::sendResponse($resultValidarToken);
            return $found = true;
        }

        if ($resultValidarToken->data->rol != Config::$ROLES['ADMINISTRADOR']['id']) {
            Response::sendResponse(new Response(403, 'Usted no tiene permisos para realizar esta acción.'));
            return $found = true;
        }

        $response = $perifericoController->delete($_PUT['id'], $headers['Token']);

        Response::sendResponse($response);
        return $found = true;
    }
    Response::sendResponse(new Response(401, 'El token es necesario.'));
    return $found = true;
}
if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_SERVER['REQUEST_URI'] == $routeBase . 'periferico/count') {
    if (isset($headers['Token'])) {
        $dbController = new DbController(Config::$DB['periferico_table']);
        $perifericoController = new PerifericoController($dbController);

        $resultValidarToken = $dbController->validarToken($headers['Token']);

        if ($resultValidarToken->status != 200) {
            Response::sendResponse($resultValidarToken);
            return $found = true;
        }

        $response = $perifericoController->count();

        Response::sendResponse($response);
        return $found = true;
    }
    Response::sendResponse(new Response(401, 'El token es necesario.'));
    return $found = true;
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_SERVER['REQUEST_URI'] == $routeBase . 'periferico/excel/all') {
    if (isset($headers['Token'])) {
        $dbController = new DbController(Config::$DB['periferico_table']);
        $perifericoController = new PerifericoController($dbController);

        $resultValidarToken = $dbController->validarToken($headers['Token']);

        if ($resultValidarToken->status != 200) {
            Response::sendResponse($resultValidarToken);
            return $found = true;
        }

        $response = $perifericoController->getAll();

        if ($response->status != 200) {
            $message = 'No se ha podido generar el Excel de periféricos: ' . strtolower($response->message);
            Response::sendResponse(new Response($response->status, $message, $response->data));
            return $found = true;
        }

        if (count($response->data) < 1) {
            $message = 'No se ha encontrado datos para generar un Excel.';
            Response::sendResponse(new Response(404, $message));
            return $found = true;
        }

        $dataForExcel = Excel::normalizeQueryData($response->data);

        $nameFileExcel = 'periferico';

        try {
            Excel::generateExcel($nameFileExcel, $dataForExcel);
        } catch (Exception $exception) {
            $message = 'No se ha podido generar el Excel de periféricos.';
            Response::sendResponse(new Response(500, $message, $exception));
            return $found = true;
        }

        return $found = true;
    }
    Response::sendResponse(new Response(401, 'El token es necesario.'));
    return $found = true;
}
if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_SERVER['REQUEST_URI'] == $routeBase . 'periferico/excel/by-tipo-dispositivo') {
    if (isset($headers['Token'])) {
        $dbController = new DbController(Config::$DB['periferico_table']);
        $perifericoController = new PerifericoController($dbController);

        $resultValidarToken = $dbController->validarToken($headers['Token']);

        if ($resultValidarToken->status != 200) {
            Response::sendResponse($resultValidarToken);
            return $found = true;
        }

        $response = $perifericoController->getAllByTipo($headers['Id']);

        if ($response->status != 200) {
            $message = 'No se ha podido generar el Excel de periféricos: ' . strtolower($response->message);
            Response::sendResponse(new Response($response->status, $message, $response->data));
            return $found = true;
        }

        if (count($response->data) < 1) {
            $message = 'No se ha encontrado datos para generar un Excel.';
            Response::sendResponse(new Response(404, $message));
            return $found = true;
        }

        $dataForExcel = Excel::normalizeQueryData($response->data);

        $nameFileExcel = 'periferico';

        try {
            Excel::generateExcel($nameFileExcel, $dataForExcel);
        } catch (Exception $exception) {
            $message = 'No se ha podido generar el Excel de periféricos.';
            Response::sendResponse(new Response(500, $message, $exception));
            return $found = true;
        }

        return $found = true;
    }
    Response::sendResponse(new Response(401, 'El token es necesario.'));
    return $found = true;
}
