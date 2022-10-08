<?php

include_once 'models/response.model.php';

class LogController
{
    public function __construct($dbController)
    {
        $this->dbController = $dbController;
    }
    public function getAll()
    {
        try {
            $data = $this->dbController->getAll();
            $message = 'El registro log se ha obtenido correctamente.';
            return new Response(200, $message, $data);
        } catch (Exception $error) {
            $message = 'Ha sucedido un error al obtener El registro log.';
            return new Response(400, $message, $error);
        }
    }
    public function insert($data)
    {
        try {
            $result =  $this->dbController->insert($data);
            $message = 'Se ha agregado un nuevo registro al log correctamente.';
            return new Response(200, $message, $result);
        } catch (Exception $error) {
            $message = 'Ha sucedido un error al agregar un nuevo registro al log correctamente.';
            return new Response(400, $message, $error);
        }
    }
}
