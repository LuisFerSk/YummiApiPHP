<?php

class tipoDispositivoController
{
    public function __construct($dbController)
    {
        $this->dbController = $dbController;
    }
    public function getAll()
    {
        try {
            $data = $this->dbController->getAll();
            $message = 'Los tipo de dispositivos se han obtenido correctamente.';
            return new Response(200, $message, $data);
        } catch (Exception $error) {
            $message = 'Ha sucedido un error al obtener los tipo de dispositivos.';
            return new Response(400, $message, $error);
        }
    }
}
