<?php

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
}
