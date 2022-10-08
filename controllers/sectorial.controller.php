<?php

include_once 'models/response.model.php';

class SectorialController
{
    public function __construct($dbController)
    {
        $this->dbController = $dbController;
    }
    public function getAll()
    {
        try {
            $data = $this->dbController->getAll();
            $message = 'Los sectoriales se han obtenido correctamente.';
            return new Response(200, $message, $data);
        } catch (Exception $error) {
            $message = 'Ha sucedido un error al obtener los sectoriales.';
            return new Response(400, $message, $error);
        }
    }
}
