<?php

class SubsectorController
{
    public function __construct($dbController)
    {
        $this->dbController = $dbController;
    }
    public function getAll()
    {
        $result = $this->dbController->getAll();
        if ($result->status != 200) {
            $message = 'Ha sucedido un error al obtener los subsectores: ' . strtolower($result->message);
            return new Response($result->status, $message, $result->data);
        }
        $message = 'Los subsectores se han obtenido correctamente.';
        return new Response($result->status, $message, $result->data);
    }
}
