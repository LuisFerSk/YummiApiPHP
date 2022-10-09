<?php

class TipoDispositivoController
{
    public function __construct($dbController)
    {
        $this->dbController = $dbController;
    }
    public function getAll()
    {
        $result = $this->dbController->getAll();
        if ($result->status != 200) {
            $message = 'Ha sucedido un error al obtener los tipo de dispositivos: ' . strtolower($result->message);
            return new Response($result->status, $message, $result->data);
        }
        $message = 'Los tipo de dispositivos se han obtenido correctamente.';
        return new Response($result->status, $message, $result->data);
    }
}
