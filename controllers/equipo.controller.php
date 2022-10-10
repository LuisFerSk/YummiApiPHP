<?php

include_once 'models/response.model.php';

class EquipoController
{
    public function __construct($dbController, $equipoModel = null)
    {
        $this->dbController = $dbController;
        $this->equipoModel = $equipoModel;
    }
    public function getAll()
    {
        $sql = 'SELECT equipos.id, equipos.idEquipo, equipos.tipo, equipos.referencia, equipos.numeroSerialCPU, equipos.numeroSerialMonitor, equipos.numeroSerialTeclado, equipos.numeroSerialMouse, equipos.direccionIP, equipos.sistemaOperativo, equipos.tipoProcesador, equipos.discoDuro, equipos.capacidad, equipos.espacioUsado, equipos.memoria, sectoriales.id AS id_sectorial, sectoriales.nombre AS sectorial, subsectores.nombre AS subsector, subsectores.id AS id_subsector, equipos.softwareInstalado, equipos.create_time, equipos.update_time FROM equipos INNER JOIN sectoriales ON equipos.sectorial = sectoriales.id LEFT JOIN subsectores ON equipos.subsector = subsectores.id';

        $result = $this->dbController->execute($sql);
        if ($result->status != 200) {
            $message = 'Ha sucedido un error al obtener los equipos: ' . strtolower($result->message);
            return new Response($result->status, $message, $result->data);
        }
        $message = 'Los equipos se han obtenido correctamente.';
        return new Response($result->status, $message, $result->data->fetchAll(PDO::FETCH_CLASS));
    }
    public function getAllBySubsector($subsector)
    {
        $sql = 'SELECT equipos.id, equipos.idEquipo, equipos.tipo, equipos.referencia, equipos.numeroSerialCPU, equipos.numeroSerialMonitor, equipos.numeroSerialTeclado, equipos.numeroSerialMouse, equipos.direccionIP, equipos.sistemaOperativo, equipos.tipoProcesador, equipos.discoDuro, equipos.capacidad, equipos.espacioUsado, equipos.memoria, sectoriales.nombre AS sectorial, subsectores.nombre AS subsector, equipos.softwareInstalado, equipos.create_time, equipos.update_time FROM equipos INNER JOIN sectoriales ON equipos.sectorial = sectoriales.id LEFT JOIN subsectores ON equipos.subsector = subsectores.id WHERE subsectores.id = ?';

        $result = $this->dbController->execute($sql, [$subsector]);

        if ($result->status != 200) {
            $message = 'Ha sucedido un error al obtener los equipos: ' . strtolower($result->message);
            return new Response($result->status, $message, $result->data);
        }

        $message = 'Los equipos se han obtenido correctamente.';
        return new Response($result->status, $message, $result->data->fetchAll(PDO::FETCH_CLASS));
    }
    public function getById($id)
    {
        $sql = 'SELECT equipos.id, equipos.idEquipo, equipos.tipo, equipos.referencia, equipos.numeroSerialCPU, equipos.numeroSerialMonitor, equipos.numeroSerialTeclado, equipos.numeroSerialMouse, equipos.direccionIP, equipos.sistemaOperativo, equipos.tipoProcesador, equipos.discoDuro, equipos.capacidad, equipos.espacioUsado, equipos.memoria, sectoriales.nombre as sectorial, subsectores.nombre as subsector, equipos.softwareInstalado, equipos.create_time, equipos.update_time FROM equipos INNER JOIN sectoriales ON equipos.sectorial = sectoriales.id LEFT JOIN subsectores ON equipos.subsector = subsectores.id WHERE equipos.id = ?';

        $result = $this->dbController->execute($sql, [$id]);
        if ($result->status != 200) {
            $message = 'Ha sucedido un error al obtener los equipos: ' . strtolower($result->message);
            return new Response($result->status, $message, $result->data);
        }

        $message = 'Los equipos se han obtenido correctamente.';
        return new Response($result->status, $message, $result->data->fetchAll(PDO::FETCH_CLASS)[0]);
    }
    public function getAllBySectorial($sectorial)
    {
        $sql = 'SELECT equipos.id, equipos.idEquipo, equipos.tipo, equipos.referencia, equipos.numeroSerialCPU, equipos.numeroSerialMonitor, equipos.numeroSerialTeclado, equipos.numeroSerialMouse, equipos.direccionIP, equipos.sistemaOperativo, equipos.tipoProcesador, equipos.discoDuro, equipos.capacidad, equipos.espacioUsado, equipos.memoria, sectoriales.nombre as sectorial, subsectores.nombre as subsector, equipos.softwareInstalado, equipos.create_time, equipos.update_time FROM equipos INNER JOIN sectoriales ON equipos.sectorial = sectoriales.id LEFT JOIN subsectores ON equipos.subsector = subsectores.id WHERE sectoriales.id = ?';

        $result = $this->dbController->execute($sql, [$sectorial]);
        if ($result->status != 200) {
            $message = 'Ha sucedido un error al obtener los equipos: ' . strtolower($result->message);
            return new Response($result->status, $message, $result->data);
        }

        $message = 'Los equipos se han obtenido correctamente.';
        return new Response($result->status, $message, $result->data->fetchAll(PDO::FETCH_CLASS));
    }
    public function getAllForExcel()
    {
        $sql = 'SELECT equipos.id, equipos.idEquipo, equipos.tipo, equipos.referencia, equipos.numeroSerialCPU, equipos.numeroSerialMonitor, equipos.numeroSerialTeclado, equipos.numeroSerialMouse, equipos.direccionIP, equipos.sistemaOperativo, equipos.tipoProcesador, equipos.discoDuro, equipos.capacidad, equipos.espacioUsado, equipos.memoria, sectoriales.nombre as sectorial, subsectores.nombre as subsector, equipos.softwareInstalado, equipos.create_time, equipos.update_time FROM equipos INNER JOIN sectoriales ON equipos.sectorial = sectoriales.id LEFT JOIN subsectores ON equipos.subsector = subsectores.id';

        $result = $this->dbController->execute($sql);
        if ($result->status != 200) {
            $message = 'Ha sucedido un error al obtener los equipos: ' . strtolower($result->message);
            return new Response($result->status, $message, $result->data);
        }

        $message = 'Los equipos se han obtenido correctamente.';
        return new Response($result->status, $message, $result->data->fetchAll(PDO::FETCH_CLASS));
    }
    public function insert($data, $token)
    {
        $resultSetEquipo = $this->equipoModel->set($data);
        if ($resultSetEquipo != 'El equipo es correcto.') {
            return new Response(400, $resultSetEquipo);
        }

        $dataToInsert = [
            "idEquipo" => $this->equipoModel->idEquipo,
            "tipo" => $this->equipoModel->tipo,
            "referencia" => $this->equipoModel->referencia,
            "numeroSerialCPU" => $this->equipoModel->numeroSerialCPU,
            "numeroSerialMonitor" => $this->equipoModel->numeroSerialMonitor,
            "numeroSerialTeclado" => $this->equipoModel->numeroSerialTeclado,
            "numeroSerialMouse" => $this->equipoModel->numeroSerialMouse,
            "direccionIP" => $this->equipoModel->direccionIP,
            "sistemaOperativo" => $this->equipoModel->sistemaOperativo,
            "softwareInstalado" => $this->equipoModel->softwareInstalado,
            "tipoProcesador" => $this->equipoModel->tipoProcesador,
            "memoria" => $this->equipoModel->memoria,
            "discoDuro" => $this->equipoModel->discoDuro,
            "capacidad" => $this->equipoModel->capacidad,
            "espacioUsado" => $this->equipoModel->espacioUsado,
            "sectorial" => $this->equipoModel->sectorial,
            "subsector" => $this->equipoModel->subsector
        ];

        $result = $this->dbController->insert($dataToInsert, $token);

        if ($result->status != 200) {
            $message = 'No se ha podido registrado el equipo: ' . strtolower($result->message);
            return new Response($result->status, $message, $result->data);
        }

        $get = $this->dbController->getById($result->data);

        $message = 'Se ha registrado el equipo correctamente.';

        if ($get->status == 200) {
            return new Response($result->status, $message, $get->data);
        }

        $dataToInsert['id'] = $result->data;

        return new Response($result->status, $message, $dataToInsert);
    }
    public function update($id, $data, $token)
    {
        $resultSetId = $this->equipoModel->setId($id);
        if ($resultSetId != "El id es correcto.") {
            return new Response(400, $resultSetId);
        }

        $resultSetEquipo = $this->equipoModel->set($data);
        if ($resultSetEquipo != 'El equipo es correcto.') {
            return new Response(400, $resultSetEquipo);
        }

        $dataToUpdate = [
            "idEquipo" => $this->equipoModel->idEquipo,
            "tipo" => $this->equipoModel->tipo,
            "referencia" => $this->equipoModel->referencia,
            "numeroSerialCPU" => $this->equipoModel->numeroSerialCPU,
            "numeroSerialMonitor" => $this->equipoModel->numeroSerialMonitor,
            "numeroSerialTeclado" => $this->equipoModel->numeroSerialTeclado,
            "numeroSerialMouse" => $this->equipoModel->numeroSerialMouse,
            "direccionIP" => $this->equipoModel->direccionIP,
            "sistemaOperativo" => $this->equipoModel->sistemaOperativo,
            "softwareInstalado" => $this->equipoModel->softwareInstalado,
            "tipoProcesador" => $this->equipoModel->tipoProcesador,
            "memoria" => $this->equipoModel->memoria,
            "discoDuro" => $this->equipoModel->discoDuro,
            "capacidad" => $this->equipoModel->capacidad,
            "espacioUsado" => $this->equipoModel->espacioUsado,
            "sectorial" => $this->equipoModel->sectorial,
            "subsector" => $this->equipoModel->subsector,
            "update_time" => $this->equipoModel->update_time
        ];

        $result = $this->dbController->update($id, $dataToUpdate, $token);

        if ($result->status == 404) {
            $message = 'No se ha encontrado el equipo.';
            return new Response($result->status, $message);
        }

        if ($result->status != 200) {
            $message = 'No se ha podido actualizar la información del equipo: ' . strtolower($result->message);
            return new Response($result->status, $message, $result->data);
        }

        $resultGet = $this->getById($result->data);

        if ($resultGet->status != 200) {
            $message = 'Se ha registrado el funcionario pero, ' . strtolower($resultGet->message);
            return new Response($result->status, $message, $resultGet->data);
        }

        $message = 'Se ha actualizado la información del equipo correctamente.';
        return new Response($resultGet->status, $message, $resultGet->data);
    }
    public function delete($id, $token)
    {
        $resultId = $this->equipoModel->setId($id);
        if ($resultId != 'El id es correcto.') {
            return new Response(400, $resultId);
        }

        $result = $this->dbController->delete($id, $token);

        if ($result->status != 200) {
            $message = 'Ha sucedido un error al eliminar el equipo: ' . strtolower($result->message);
            return new Response($result->status, $message, $result->data);
        }

        $message = 'El equipo se ha eliminado correctamente.';
        return new Response(200, $message);
    }

    public function count()
    {
        $result = $this->dbController->count();
        if ($result->status != 200) {
            $message = 'Ha sucedido un error al obtener el total de equipos: ' . $result->message;
            return new Response($result->status, $message, $result->data);
        }
        $message = 'El total de equipos se ha obtenido correctamente.';
        return new Response($result->status, $message, $result->data);
    }
}
