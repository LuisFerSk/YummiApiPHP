<?php

include_once 'models/response.model.php';

class FuncionarioController
{
    public function __construct($db, $funcionario = null)
    {
        $this->db = $db;
        $this->funcionario = $funcionario;
    }
    public function getAllBySubsector($subsector)
    {
        $sql = "SELECT funcionarios.id, funcionarios.identificacion, funcionarios.nombre, sectoriales.id AS id_sectorial, sectoriales.nombre AS sectorial, subsectores.id AS id_subsector, subsectores.nombre AS subsector, funcionarios.create_time, funcionarios.update_time FROM funcionarios INNER JOIN sectoriales ON funcionarios.sectorial = sectoriales.id LEFT JOIN subsectores ON funcionarios.subsector = subsectores.id WHERE subsectores.id = ?";

        try {
            $data = $this->dbController->execute($sql, [$subsector]);
            $message = 'Los funcionarios se han obtenido correctamente.';
            return new Response(200, $message, $data);
        } catch (Exception $error) {
            $message = 'Ha sucedido un error al obtener los funcionarios.';
            return new Response(400, $message, $error);
        }
    }
    public function getAllBySectorial($sectorial)
    {
        $sql = "SELECT funcionarios.id, funcionarios.identificacion, funcionarios.nombre, sectoriales.id as id_sectorial, sectoriales.nombre as sectorial, subsectores.id as id_subsector, subsectores.nombre as subsector, funcionarios.create_time, funcionarios.update_time FROM funcionarios INNER JOIN sectoriales ON funcionarios.sectorial = sectoriales.id LEFT JOIN subsectores ON funcionarios.subsector = subsectores.id WHERE sectoriales.id = ?";

        try {
            $data = $this->dbController->execute($sql, [$sectorial]);
            $message = 'Los funcionarios se han obtenido correctamente.';
            return new Response(200, $message, $data);
        } catch (Exception $error) {
            $message = 'Ha sucedido un error al obtener los funcionarios.';
            return new Response(400, $message, $error);
        }
    }
    public function getAll()
    {
        $sql = "SELECT funcionarios.id, funcionarios.identificacion, funcionarios.nombre, sectoriales.id as id_sectorial, sectoriales.nombre as sectorial, subsectores.id as id_subsector, subsectores.nombre as subsector, funcionarios.create_time, funcionarios.update_time FROM funcionarios INNER JOIN sectoriales ON funcionarios.sectorial = sectoriales.id LEFT JOIN subsectores ON funcionarios.subsector = subsectores.id";

        try {
            $data = $this->dbController->execute($sql);
            $message = 'Los funcionarios se han obtenido correctamente.';
            return new Response(200, $message, $data);
        } catch (Exception $error) {
            $message = 'Ha sucedido un error al obtener los funcionarios.';
            return new Response(400, $message, $error);
        }
    }
    public function getActived()
    {
        try {
            $data = $this->dbController->getBy('estado', 1);
            $message = 'Los funcionarios se han obtenido correctamente.';
            return new Response(200, $message, $data);
        } catch (Exception $error) {
            $message = 'Ha sucedido un error al obtener los funcionarios.';
            return new Response(400, $message, $error);
        }
    }
    public function getById($id)
    {
        $sql = "SELECT funcionarios.id, funcionarios.identificacion, funcionarios.nombre, sectoriales.id as id_sectorial, sectoriales.nombre as sectorial, subsectores.id as id_subsector, subsectores.nombre as subsector, funcionarios.create_time, funcionarios.update_time FROM funcionarios INNER JOIN sectoriales ON funcionarios.sectorial = sectoriales.id LEFT JOIN subsectores ON funcionarios.subsector = subsectores.id WHERE funcionarios.id = ?";

        try {
            $data = $this->dbController->execute($sql, [$id]);
            $message = 'Los funcionarios se han obtenido correctamente.';
            return new Response(200, $message, $data);
        } catch (Exception $error) {
            $message = 'Ha sucedido un error al obtener los funcionarios.';
            return new Response(400, $message, $error);
        }
    }
    public function insert($funcionario)
    {
        $resultSetFunctionario = $this->funcionario->setFuncionario($funcionario);
        if ($resultSetFunctionario != 'El funcionario es correcto.') {
            return new Response(400, $resultSetFunctionario);
        }

        $dataToInsert = [
            'identificacion' => $this->funcionario->identificacion,
            'nombre' => $this->funcionario->nombre,
            'sectorial' => $this->funcionario->sectorial,
            'subsector' => $this->funcionario->subsector
        ];

        try {
            $result = $this->dbController->insert($dataToInsert);
            $message = 'Se ha insertado el funcionario correctamente.';
            return new Response(200, $message, $result);
        } catch (Exception $error) {
            $message = 'Ha sucedido un error al insertar el funcionario funcionarios.';
            return new Response(400, $message, $error);
        }
    }
    public function update($id, $funcionario)
    {
        $resultSetId = $this->funcionario->setId($id);
        if ($resultSetId != 'El id es correcto.') {
            return new Response(400, $resultSetId);
        }

        $resultSetFuncionario = $this->funcionario->setFuncionario($funcionario);
        if ($resultSetFuncionario != 'El funcionario es correcto.') {
            return new Response(400, $resultSetFuncionario);
        }

        $newData = [
            "identificacion" => $this->funcionario->identificacion,
            "nombre" => $this->funcionario->nombre,
            "sectorial" => $this->funcionario->sectorial,
            "subsector" => $this->funcionario->subsector,
            "update_time" =>  $this->functionario->update_time
        ];
        try {
            $result = $this->dbController->update($this->funcionario->id, $newData);
            $message = 'Se ha actualizado el funcionario correctamente.';
            return new Response(200, $message, $result);
        } catch (Exception $error) {
            $message = 'Ha sucedido un error al actualizar el funcionario funcionarios.';
            return new Response(400, $message, $error);
        }
    }
    public function delete($id)
    {
        $resultSetId = $this->funcionario->setId($id);
        if ($resultSetId != 'El id es correcto.') {
            return new Response(400, $resultSetId);
        }
        try {
            $result = $this->dbController->delete($this->funcionario->id);
            $message = 'El funcionario se ha eliminado correctamente.';
            return new Response(200, $message, $result);
        } catch (Exception $error) {
            $message = 'Ha sucedido un error al eliminar el funcionario.';
            return new Response(400, $message, $error);
        }
    }
    public function count()
    {
        try {
            $result = $this->dbController->count();
            $message = 'El total de funcionario se ha obtenido correctamente.';
            return new Response(200, $message, $result);
        } catch (Exception $error) {
            $message = 'Ha sucedido un error al obtener el total de funcionarios.';
            return new Response(400, $message, $error);
        }
    }
}
