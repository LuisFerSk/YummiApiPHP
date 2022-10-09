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

        $result = $this->dbController->getAll();

        if ($result->status != 200) {
            $message = 'Ha sucedido un error al obtener los perifericos: ' . strtolower($result->message);
            return new Response($result->status, $message, $result->data);
        }
        $message = 'Los perifericos se han obtenido correctamente.';
        return new Response($result->status, $message, $result->data);
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
    public function insert($periferico, $token)
    {
        $resultSetPeriferico = $this->perifericoModel->setPeriferico($periferico);
        if ($resultSetPeriferico != 'El periferico es correcto.') {
            return new Response(400, $resultSetPeriferico);
        }

        $dataToInsert = [
            "tipo_dispositivo" => $this->perifericoModel->tipo_dispositivo,
            "referenciaPeriferico" => $this->perifericoModel->referenciaPeriferico,
            "numeroSerial" => $this->perifericoModel->numeroSerial,
            "estado" => $this->perifericoModel->estado,
            "observaciones" => $this->perifericoModel->observaciones
        ];

        $result = $this->dbController->insert($dataToInsert, $token);

        if ($result->status != 200) {
            $message = 'No se ha podido registrado el periferico: ' . strtolower($result->message);
            return new Response($result->status, $message, $result->data);
        }

        $get = $this->dbController->getById($result->data);

        $message = 'Se ha registrado el periferico correctamente.';

        if ($get->status == 200) {
            return new Response($result->status, $message, $get->data);
        }

        $dataToInsert['id'] = $result->data;

        return new Response($result->status, $message, $dataToInsert);
    }
    public function update($id, $periferico, $token)
    {
        $resultSetId = $this->perifericoModel->setId($id);
        if ($resultSetId != 'El id es correcto.') {
            return new Response(400, $resultSetId);
        }

        $resultSetPeriferico = $this->perifericoModel->setPeriferico($periferico);
        if ($resultSetPeriferico != 'El periferico es correcto.') {
            return new Response(400, $resultSetPeriferico);
        }

        $dataToUpdate = [
            "tipo_dispositivo" => $this->perifericoModel->tipo_dispositivo,
            "referenciaPeriferico" => $this->perifericoModel->referenciaPeriferico,
            "numeroSerial" => $this->perifericoModel->numeroSerial,
            "estado" => $this->perifericoModel->estado,
            "observaciones" => $this->perifericoModel->observaciones,
            "update_time" => $this->perifericoModel->update_time
        ];

        $result = $this->dbController->update($id, $dataToUpdate, $token);

        if ($result->status == 404) {
            $message = 'No se ha encontrado el periferico.';
            return new Response($result->status, $message);
        }

        if ($result->status != 200) {
            $message = 'No se ha podido actualizar la información del periferico: ' . strtolower($result->message);
            return new Response($result->status, $message, $result->data);
        }

        $message = 'Se ha actualizado la información del periferico correctamente.';
        return new Response(200, $message);
    }
    public function delete($id, $token)
    {
        $resultId = $this->perifericoModel->setId($id);
        if ($resultId != 'El id es correcto.') {
            return new Response(400, $resultId);
        }

        $result = $this->dbController->delete($id, $token);

        if ($result->status != 200) {
            $message = 'Ha sucedido un error al eliminar el periferico: ' . strtolower($result->message);
            return new Response($result->status, $message, $result->data);
        }

        $message = 'El periferico se ha eliminado correctamente.';
        return new Response(200, $message);
    }
    public function count()
    {
        $result = $this->dbController->count();
        if ($result->status != 200) {
            $message = 'Ha sucedido un error al obtener el total de perifericos: ' . $result->message;
            return new Response($result->status, $message, $result->data);
        }
        $message = 'El total de perifericos se ha obtenido correctamente.';
        return new Response($result->status, $message, $result->data);
    }
}
