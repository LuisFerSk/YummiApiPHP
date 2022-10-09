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
        $result = $this->dbController->getAll();
        if (is_array($result)) {
            $message = 'Los equipos se han obtenido correctamente.';
            return new Response(200, $message, $result);
        }

        $message = 'Ha sucedido un error al obtener los equipos.';
        return new Response(400, $message, $result);
    }
    public function getAllBySubsector($subsector)
    {
        $sql = 'SELECT equipos.id, equipos.idEquipo, equipos.tipo, equipos.referencia, equipos.numeroSerialCPU, equipos.numeroSerialMonitor, equipos.numeroSerialTeclado, equipos.numeroSerialMouse, equipos.direccionIP, equipos.sistemaOperativo, equipos.tipoProcesador, equipos.discoDuro, equipos.capacidad, equipos.espacioUsado, equipos.memoria, sectoriales.nombre AS sectorial, subsectores.nombre AS subsector, equipos.softwareInstalado, equipos.create_time, equipos.update_time FROM equipos INNER JOIN sectoriales ON equipos.sectorial = sectoriales.id LEFT JOIN subsectores ON equipos.subsector = subsectores.id WHERE subsectores.id = ?';

        $result = $this->dbController->execute($sql, [$subsector]);
        $message = 'Los equipos se han obtenido correctamente.';
        return new Response(200, $message, $result);

        $message = 'Ha sucedido un error al obtener los equipos.';
        return new Response(400, $message, $result);
    }
    public function getAllBySectorial($sectorial)
    {
        $sql = 'SELECT equipos.id, equipos.idEquipo, equipos.tipo, equipos.referencia, equipos.numeroSerialCPU, equipos.numeroSerialMonitor, equipos.numeroSerialTeclado, equipos.numeroSerialMouse, equipos.direccionIP, equipos.sistemaOperativo, equipos.tipoProcesador, equipos.discoDuro, equipos.capacidad, equipos.espacioUsado, equipos.memoria, sectoriales.nombre as sectorial, subsectores.nombre as subsector, equipos.softwareInstalado, equipos.create_time, equipos.update_time FROM equipos INNER JOIN sectoriales ON equipos.sectorial = sectoriales.id LEFT JOIN subsectores ON equipos.subsector = subsectores.id WHERE sectoriales.id = ?';

        $result = $this->dbController->execute($sql, [$sectorial]);
        $message = 'Los equipos se han obtenido correctamente.';
        return new Response(200, $message, $result);

        $message = 'Ha sucedido un error al obtener los equipos.';
        return new Response(400, $message, $result);
    }
    public function getAllForExcel()
    {
        $sql = 'SELECT equipos.id, equipos.idEquipo, equipos.tipo, equipos.referencia, equipos.numeroSerialCPU, equipos.numeroSerialMonitor, equipos.numeroSerialTeclado, equipos.numeroSerialMouse, equipos.direccionIP, equipos.sistemaOperativo, equipos.tipoProcesador, equipos.discoDuro, equipos.capacidad, equipos.espacioUsado, equipos.memoria, sectoriales.nombre as sectorial, subsectores.nombre as subsector, equipos.softwareInstalado, equipos.create_time, equipos.update_time FROM equipos INNER JOIN sectoriales ON equipos.sectorial = sectoriales.id LEFT JOIN subsectores ON equipos.subsector = subsectores.id';

        $result = $this->dbController->execute($sql);
        $message = 'Los equipos se han obtenido correctamente.';
        return new Response(200, $message, $result);

        $message = 'Ha sucedido un error al obtener los equipos.';
        return new Response(400, $message, $result);
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
            $message = 'No se ha podido registrado el equipo.';
            return new Response($result->status, $message, $result->data);
        }

        $get = $this->dbController->getById($result->data);

        $message = 'Se ha registrado el equipo correctamente.';

        if ($get->status == 200) {
            return new Response(200, $message, $get->data);
        }

        $dataToInsert['id'] = $result->data->id;

        return new Response(200, $message, $dataToInsert);
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
            $message = 'No se ha podido actualizar la información del equipo.';
            return new Response($result->status, $message, $result->data);
        }

        $message = 'Se ha actualizado la información del equipo correctamente.';
        return new Response(200, $message);
    }
    public function delete($id)
    {
        $resultId = $this->equipoModel->setId($id);
        if ($resultId != 'El id es correcto.') {
            return new Response(400, $resultId);
        }

        $result = $this->dbController->delete($id);

        if (!is_object($result) && is_int($result) && $result > 0) {
            $message = 'El equipo se ha eliminado correctamente.';
            return new Response(200, $message, $result);
        }

        $message = 'Ha sucedido un error al eliminar el equipo.';
        return new Response(400, $message, $result);
    }

    public function count()
    {
        $result = $this->dbController->count();
        $message = 'El total de equipos se ha obtenido correctamente.';
        return new Response(200, $message, $result);

        $message = 'Ha sucedido un error al obtener el total de equipos.';
        return new Response(400, $message, $result);
    }
}
