<?php

class LogController
{
    public function __construct($dbController)
    {
        $this->dbController = $dbController;
    }
    public function getAll()
    {
        $result = $this->dbController->getAll();

        if ($result->status != 200) {
            $message = 'Ha sucedido un error al obtener El registro log: ' . strtolower($result->message);
            return new Response($result->status, $message, $result->data);
        }

        $message = 'El registro log se ha obtenido correctamente.';
        return new Response($result->status, $message, $result->data);
    }
}
