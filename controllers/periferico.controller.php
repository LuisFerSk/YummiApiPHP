<?php

include_once 'models/response.model.php';

class PerifericoController
{
    public function __construct($dbController, $perifericoModel = null)
    {
        $this->dbController = $dbController;
        $this->perifericoModel = $perifericoModel;
    }
    public function getAllByTipo($tipo)
    {
        $sql = "SELECT perifericos.id,tipo_dispositivos.nombre AS tipo_dispositivo, estados.nombre AS estado, perifericos.referenciaPeriferico, perifericos.numeroSerial, perifericos.observaciones, perifericos.create_time, perifericos.update_time FROM perifericos LEFT JOIN tipo_dispositivos ON perifericos.tipo_dispositivo = tipo_dispositivos.id LEFT JOIN estados ON estados.id = perifericos.estado WHERE tipo_dispositivos.id = ?";

        try {
            $data = $this->dbController->execute($sql, [$tipo]);
            $message = 'Los equipos se han obtenido correctamente.';
            return new Response(200, $message, $data);
        } catch (Exception $error) {
            $message = 'Ha sucedido un error al obtener los equipos.';
            return new Response(400, $message, $error);
        }
    }
    public function getAllForExcel()
    {
        $sql = "SELECT perifericos.id,tipo_dispositivos.nombre AS tipo_dispositivo, estados.nombre AS estado, perifericos.referenciaPeriferico, perifericos.numeroSerial, perifericos.observaciones, perifericos.create_time, perifericos.update_time FROM perifericos LEFT JOIN tipo_dispositivos ON perifericos.tipo_dispositivo = tipo_dispositivos.id LEFT JOIN estados ON estados.id = perifericos.estado";
        try {
            $data = $this->dbController->execute($sql);
            $message = 'Los equipos se han obtenido correctamente.';
            return new Response(200, $message, $data);
        } catch (Exception $error) {
            $message = 'Ha sucedido un error al obtener los equipos.';
            return new Response(400, $message, $error);
        }
    }
    public function getAll()
    {
        $sql = "SELECT perifericos.id,tipo_dispositivos.nombre AS tipo_dispositivo, tipo_dispositivos.id AS id_tipo_dispositivo, perifericos.referenciaPeriferico, perifericos.numeroSerial, perifericos.estado, perifericos.observaciones, perifericos.create_time, perifericos.update_time FROM perifericos LEFT JOIN tipo_dispositivos ON perifericos.tipo_dispositivo = tipo_dispositivos.id";

        try {
            $data = $this->dbController->execute($sql);
            $message = 'Los equipos se han obtenido correctamente.';
            return new Response(200, $message, $data);
        } catch (Exception $error) {
            $message = 'Ha sucedido un error al obtener los equipos.';
            return new Response(400, $message, $error);
        }
    }
    public function getActived()
    {
        try {
            $data = $this->dbController->getBy('estado', 1);
            $message = 'Los equipos se han obtenido correctamente.';
            return new Response(200, $message, $data);
        } catch (Exception $error) {
            $message = 'Ha sucedido un error al obtener los equipos.';
            return new Response(400, $message, $error);
        }
    }
    public function getById($id)
    {
        $sql = "SELECT perifericos.id,tipo_dispositivos.nombre AS tipo_dispositivo, tipo_dispositivos.id AS id_tipo_dispositivo, perifericos.referenciaPeriferico, perifericos.numeroSerial, perifericos.estado, perifericos.observaciones, perifericos.create_time, perifericos.update_time FROM perifericos LEFT JOIN tipo_dispositivos ON perifericos.tipo_dispositivo = tipo_dispositivos.id WHERE perifericos.id = ?";
        try {
            $data = $this->dbController->execute($sql, [$id]);
            $message = 'Los equipos se han obtenido correctamente.';
            return new Response(200, $message, $data);
        } catch (Exception $error) {
            $message = 'Ha sucedido un error al obtener los equipos.';
            return new Response(400, $message, $error);
        }
    }
    public function insert($periferico)
    {
        $resultSetPeriferico = $this->periferico->setPeriferico($periferico);
        if ($resultSetPeriferico != 'El periferico es correcto.') {
            return new Response(400, $resultSetPeriferico);
        }

        $dataToInsert = [
            "tipo_dispositivo" => $this->periferico->tipo_dispositivo,
            "referenciaPeriferico" => $this->periferico->referenciaPeriferico,
            "numeroSerial" => $this->periferico->numeroSerial,
            "estado" => $this->periferico->estado,
            "observaciones" => $this->periferico->observaciones
        ];

        try {
            $result = $this->dbController->insert($dataToInsert);
            $message = 'El periferico se ha insertado correctamente.';
            return new Response(200, $message, $result);
        } catch (Exception $error) {
            $message = 'Ha sucedido un error al insertar el periferico.';
            return new Response(400, $message, $error);
        }
    }
    public function update($id, $periferico)
    {
        $resultSetId = $this->periferico->setId($id);
        if ($resultSetId != 'El id es correcto.') {
            return new Response(400, $resultSetId);
        }

        $resultSetPeriferico = $this->periferico->setPeriferico($periferico);
        if ($resultSetPeriferico != 'El periferico es correcto.') {
            return new Response(400, $resultSetPeriferico);
        }

        $newData = [
            "tipo_dispositivo" => $this->periferico->tipo_dispositivo,
            "referenciaPeriferico" => $this->periferico->referenciaPeriferico,
            "numeroSerial" => $this->periferico->numeroSerial,
            "estado" => $this->periferico->estado,
            "observaciones" => $this->periferico->observaciones,
            "update_time" => $this->periferico->update_time
        ];

        try {
            $result = $this->dbController->update($this->periferico->id, $newData);
            $message = 'Se ha actualizado la información del periferico correctamente.';
            return new Response(200, $message, $result);
        } catch (Exception $error) {
            $message = 'Ha sucedido un error al actualizar la información del periferico.';
            return new Response(400, $message, $error);
        }
    }
    public function delete($id)
    {
        $resultSetId = $this->periferico->setId($id);
        if ($resultSetId != 'El id es correcto.') {
            return new Response(400, $resultSetId);
        }

        try {
            $result = $this->dbController->delete($this->periferico->id);
            $message = 'El periferico se ha eliminado correctamente.';
            return new Response(200, $message, $result);
        } catch (Exception $error) {
            $message = 'Ha sucedido un error al eliminar el periferico.';
            return new Response(400, $message, $error);
        }
    }
    public function count()
    {
        try {
            $result = $this->dbController->count();
            $message = 'El total de perifericos se ha obtenido correctamente.';
            return new Response(200, $message, $result);
        } catch (Exception $error) {
            $message = 'Ha sucedido un error al obtener el total de perifericos.';
            return new Response(400, $message, $error);
        }
    }
}
