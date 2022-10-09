<?php

include_once 'models/response.model.php';

class FuncionarioController
{
    public function __construct($dbController, $funcionarioModel = null)
    {
        $this->dbController = $dbController;
        $this->funcionarioModel = $funcionarioModel;
    }
    public function getAllBySubsector($subsector)
    {
        $sql = "SELECT funcionarios.id, funcionarios.identificacion, funcionarios.nombre, sectoriales.id AS id_sectorial, sectoriales.nombre AS sectorial, subsectores.id AS id_subsector, subsectores.nombre AS subsector, funcionarios.create_time, funcionarios.update_time FROM funcionarios INNER JOIN sectoriales ON funcionarios.sectorial = sectoriales.id LEFT JOIN subsectores ON funcionarios.subsector = subsectores.id WHERE subsectores.id = ?";

        $result = $this->dbController->execute($sql, [$subsector]);

        if ($result->status != 200) {
            $message = 'Ha sucedido un error al obtener los funcionarios: ' . strtolower($result->message);
            return new Response($result->status, $message, $result->data);
        }

        $message = 'Los funcionarios se han obtenido correctamente.';
        return new Response($result->status, $message, $result->data->fetchAll(PDO::FETCH_CLASS));
    }
    public function getAllBySectorial($sectorial)
    {
        $sql = "SELECT funcionarios.id, funcionarios.identificacion, funcionarios.nombre, sectoriales.id as id_sectorial, sectoriales.nombre as sectorial, subsectores.id as id_subsector, subsectores.nombre as subsector, funcionarios.create_time, funcionarios.update_time FROM funcionarios INNER JOIN sectoriales ON funcionarios.sectorial = sectoriales.id LEFT JOIN subsectores ON funcionarios.subsector = subsectores.id WHERE sectoriales.id = ?";

        $result = $this->dbController->execute($sql, [$sectorial]);

        if ($result->status != 200) {
            $message = 'Los funcionarios se han obtenido correctamente: ' . strtolower($result->message);
            return new Response($result->status, $message, $result->data);
        }

        $message = 'Ha sucedido un error al obtener los funcionarios.';
        return new Response($result->status, $message, $result->data->fetchAll(PDO::FETCH_CLASS));
    }
    public function getAll()
    {
        $sql = "SELECT funcionarios.id, funcionarios.identificacion, funcionarios.nombre, sectoriales.id AS id_sectorial, sectoriales.nombre AS sectorial, subsectores.id AS id_subsector, subsectores.nombre AS subsector, funcionarios.create_time, funcionarios.update_time FROM funcionarios INNER JOIN sectoriales ON funcionarios.sectorial = sectoriales.id LEFT JOIN subsectores ON funcionarios.subsector = subsectores.id";

        $result = $this->dbController->execute($sql);

        if ($result->status != 200) {
            $message = 'Ha sucedido un error al obtener los funcionarios: ' . strtolower($result->message);
            return new Response($result->status, $message, $result->data);
        }

        $message = 'Los funcionarios se han obtenido correctamente.';
        return new Response($result->status, $message, $result->data->fetchAll(PDO::FETCH_CLASS));
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
    public function insert($funcionario, $token)
    {
        $resultSetFunctionario = $this->funcionarioModel->setFuncionario($funcionario);
        if ($resultSetFunctionario != 'El funcionario es correcto.') {
            return new Response(400, $resultSetFunctionario);
        }

        $dataToInsert = [
            'identificacion' => $this->funcionarioModel->identificacion,
            'nombre' => $this->funcionarioModel->nombre,
            'sectorial' => $this->funcionarioModel->sectorial,
            'subsector' => $this->funcionarioModel->subsector
        ];

        $result = $this->dbController->insert($dataToInsert, $token);

        if ($result->status != 200) {
            $message = 'No se ha podido registrado el funcionario: ' . strtolower($result->message);
            return new Response($result->status, $message, $result->data);
        }

        $get = $this->dbController->getById($result->data);

        $message = 'Se ha registrado el funcionario correctamente.';

        if ($get->status == 200) {
            return new Response($result->status, $message, $get->data);
        }

        $dataToInsert['id'] = $result->data;

        return new Response($result->status, $message, $dataToInsert);
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
