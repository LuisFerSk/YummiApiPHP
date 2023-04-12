<?php

if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_SERVER['REQUEST_URI'] == $routeBase . 'equipo') {
    if (isset($headers['Token'])) {
        $dbController = new DbController(Config::$DB['equipo_table']);
        $equipoController = new EquipoController($dbController);

        $resultValidarToken = $dbController->validarToken($headers['Token']);

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
    if (isset($headers['Token'])) {
        $dbController = new DbController(Config::$DB['equipo_table']);
        $equipoModel = new Equipo();
        $equipoController = new EquipoController($dbController, $equipoModel);

        $resultValidarToken = $dbController->validarToken($headers['Token']);

        if ($resultValidarToken->status != 200) {
            Response::sendResponse($resultValidarToken);
            return $found = true;
        }

        $response = $equipoController->insert($_POST, $headers['Token']);

        Response::sendResponse($response);
        return $found = true;
    }
    Response::sendResponse(new Response(401, 'El token es obligatorio.'));
    return $found = true;
}
if ($_SERVER['REQUEST_METHOD'] == 'PUT' && $_SERVER['REQUEST_URI'] == $routeBase . 'equipo') {
    if (isset($headers['Token'])) {
        $dbController = new DbController(Config::$DB['equipo_table']);
        $equipoModel = new Equipo();
        $equipoController = new EquipoController($dbController, $equipoModel);

        $resultValidarToken = $dbController->validarToken($headers['Token']);

        if ($resultValidarToken->status != 200) {
            Response::sendResponse($resultValidarToken);
            return $found = true;
        }

        $response = $equipoController->update($_PUT['id'], $_PUT, $headers['Token']);

        Response::sendResponse($response);
        return $found = true;
    }
    Response::sendResponse(new Response(401, 'El token es obligatorio.'));
}
if ($_SERVER['REQUEST_METHOD'] == 'DELETE' && $_SERVER['REQUEST_URI'] == $routeBase . 'equipo') {
    if (isset($headers['Token'])) {
        $dbController = new DbController(Config::$DB['equipo_table']);
        $equipoModel = new Equipo();
        $equipoController = new EquipoController($dbController, $equipoModel);

        $resultValidarToken = $dbController->validarToken($headers['Token']);

        if ($resultValidarToken->status != 200) {
            Response::sendResponse($resultValidarToken);
            return $found = true;
        }
        if ($resultValidarToken->data->rol != Config::$ROLES['ADMINISTRADOR']['id']) {
            Response::sendResponse(new Response(403, 'Usted no tiene permisos para realizar esta acción.'));
            return $found = true;
        }

        $response = $equipoController->delete($_PUT['id'], $headers['Token']);

        Response::sendResponse($response);
        return $found = true;
    }
    Response::sendResponse(new Response(401, 'El token es necesario.'));
    return $found = true;
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_SERVER['REQUEST_URI'] == $routeBase . 'equipo/count') {
    if (isset($headers['Token'])) {
        $dbController = new DbController(Config::$DB['equipo_table']);
        $equipoController = new EquipoController($dbController);

        $resultValidarToken = $dbController->validarToken($headers['Token']);

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
if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_SERVER['REQUEST_URI'] == $routeBase . 'equipo/excel/all') {
    if (isset($headers['Token'])) {
        $dbController = new DbController(Config::$DB['equipo_table']);
        $equipoController = new EquipoController($dbController);

        $resultValidarToken = $dbController->validarToken($headers['Token']);

        if ($resultValidarToken->status != 200) {
            Response::sendResponse($resultValidarToken);
            return $found = true;
        }

        $response = $equipoController->getAll();

        if ($response->status != 200) {
            $message = 'No se ha podido generar el Excel de equipos: ' . strtolower($response->message);
            Response::sendResponse(new Response($response->status, $message, $response->data));
            return $found = true;
        }

        if (count($response->data) < 1) {
            $message = 'No se ha encontrado datos para generar un Excel.';
            Response::sendResponse(new Response(404, $message));
            return $found = true;
        }

        $dataForExcel = Excel::normalizeQueryData($response->data);

        $nameFileExcel = 'equipo';

        try {
            Excel::generateExcel($nameFileExcel, $dataForExcel);
        } catch (Exception $exception) {
            $message = 'No se ha podido generar el Excel de equipos.';
            Response::sendResponse(new Response(500, $message, $exception));
            return $found = true;
        }

        return $found = true;
    }
    Response::sendResponse(new Response(401, 'El token es necesario.'));
    return $found = true;
}
if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_SERVER['REQUEST_URI'] == $routeBase . 'equipo/excel/by-sectorial') {
    if (isset($headers['Token'])) {
        $dbController = new DbController(Config::$DB['equipo_table']);
        $equipoController = new EquipoController($dbController);

        $resultValidarToken = $dbController->validarToken($headers['Token']);

        if ($resultValidarToken->status != 200) {
            Response::sendResponse($resultValidarToken);
            return $found = true;
        }

        $response = $equipoController->getAllBySectorial($headers['Id']);

        if ($response->status != 200) {
            $message = 'No se ha podido generar el Excel de equipos: ' . strtolower($response->message);
            Response::sendResponse(new Response($response->status, $message, $response->data));
            return $found = true;
        }

        if (count($response->data) < 1) {
            $message = 'No se ha encontrado datos para generar un Excel.';
            Response::sendResponse(new Response(404, $message));
            return $found = true;
        }

        $dataForExcel = Excel::normalizeQueryData($response->data);

        $nameFileExcel = 'equipo';

        try {
            Excel::generateExcel($nameFileExcel, $dataForExcel);
        } catch (Exception $exception) {
            $message = 'No se ha podido generar el Excel de equipos.';
            Response::sendResponse(new Response(500, $message, $exception));
            return $found = true;
        }

        return $found = true;
    }
    Response::sendResponse(new Response(401, 'El token es necesario.'));
    return $found = true;
}
if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_SERVER['REQUEST_URI'] == $routeBase . 'equipo/excel/by-subsector') {
    if (isset($headers['Token'])) {
        $dbController = new DbController(Config::$DB['equipo_table']);
        $equipoController = new EquipoController($dbController);

        $resultValidarToken = $dbController->validarToken($headers['Token']);

        if ($resultValidarToken->status != 200) {
            Response::sendResponse($resultValidarToken);
            return $found = true;
        }

        $response = $equipoController->getAllBySubsector($headers['Id']);

        if ($response->status != 200) {
            $message = 'No se ha podido generar el Excel de equipos: ' . strtolower($response->message);
            Response::sendResponse(new Response($response->status, $message, $response->data));
            return $found = true;
        }

        if (count($response->data) < 1) {
            $message = 'No se ha encontrado datos para generar un Excel.';
            Response::sendResponse(new Response(404, $message));
            return $found = true;
        }

        $dataForExcel = Excel::normalizeQueryData($response->data);

        $nameFileExcel = 'equipo';

        try {
            Excel::generateExcel($nameFileExcel, $dataForExcel);
        } catch (Exception $exception) {
            $message = 'No se ha podido generar el Excel de equipos.';
            Response::sendResponse(new Response(500, $message, $exception));
            return $found = true;
        }

        return $found = true;
    }
    Response::sendResponse(new Response(401, 'El token es necesario.'));
    return $found = true;
}
