<?php

if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_SERVER['REQUEST_URI'] == $routeBase . 'log/excel') {
    if (isset($headers['token'])) {
        $dbController = new DbController(Config::$DB['log_table']);
        $logController = new LogController($dbController);

        $resultValidarToken = $dbController->validarToken($headers['token']);

        if ($resultValidarToken->status != 200) {
            Response::sendResponse($resultValidarToken);
            return $found = true;
        }

        if ($resultValidarToken->data->rol != Config::$ROLES['ADMINISTRADOR']['id']) {
            Response::sendResponse(new Response(403, 'Usted no tiene permisos para realizar esta acción.'));
            return $found = true;
        }

        $response = $logController->getAll();

        if ($response->status != 200) {
            $message = 'No se ha podido generar el registro log: ' . strtolower($response->message);
            Response::sendResponse(new Response($response->status, $message, $response->data));
            return $found = true;
        }

        if (count($response->data) < 1) {
            $message = 'No se ha encontrado datos para generar el registro log.';
            Response::sendResponse(new Response(404, $message));
            return $found = true;
        }

        $dataForExcel = Excel::normalizeQueryData($response->data);

        $nameFileExcel = 'log';

        try {
            Excel::generateExcel($nameFileExcel, $dataForExcel);
        } catch (Exception $exception) {
            $message = 'No se ha podido generar el registro log.';
            Response::sendResponse(new Response(500, $message, $exception));
            return $found = true;
        }

        return $found = true;
    }
    Response::sendResponse(new Response(401, 'El token es necesario.'));
    return $found = true;
}
