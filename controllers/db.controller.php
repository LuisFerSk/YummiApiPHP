<?php

include_once 'database/index.php';

class DbController extends DataBase
{
    public function __construct($tableName)
    {
        parent::__construct();

        $this->tableName = $tableName;
    }
    public function execute($sql, $vars = [])
    {
        $conection = $this->connect();

        $statement = $conection->prepare($sql);

        try {
            $statement->execute($vars);
            return $statement;
        } catch (PDOException $exception) {
            return $exception;
        }
    }
    public function getAll()
    {
        $sql = 'SELECT * FROM ' . $this->tableName;

        $conection = $this->connect();

        $statement = $conection->prepare($sql);

        try {
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_CLASS);
        } catch (PDOException $exception) {
            return $exception;
        }
    }
    public function getBy($column, $value)
    {
        $sql = 'SELECT * FROM ' . $this->tableName . ' WHERE ' . $column . ' = "' . $value . '"';

        $conection = $this->connect();

        $statement = $conection->prepare($sql);

        try {
            $statement->execute([]);
            $message = 'Se ha obtenido la información correctamente.';
            return new Response(200, $message, $statement->fetchAll(PDO::FETCH_CLASS));
        } catch (PDOException $exception) {
            $message = 'No se ha podido obtener la información.';
            return new Response(400, $message, $exception);
        }
    }
    public function getById($id)
    {
        return $this->getBy('id', $id);
    }
    public function count()
    {
        $sql = 'SELECT COUNT(*) as count FROM ' . $this->tableName;

        $conection = $this->connect();

        $statement = $conection->prepare($sql);

        try {
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_CLASS);
        } catch (PDOException $exception) {
            return $exception;
        }
    }

    public function delete($id)
    {
        $sql = 'DELETE FROM ' . $this->tableName . ' WHERE id = ?';

        $conection = $this->connect();

        $statement = $conection->prepare($sql);

        try {
            $statement->execute([$id]);
            $message = 'Se ha eliminado correctamente el registro.';
            return new Response(200, $message, $statement->rowCount());
        } catch (PDOException $exception) {
            $message = 'No se ha podido eliminar el registro.';
            return new Response(200, $message, $exception);
        }
    }
    static private function getSqlInsert($data, $table)
    {
        $values = [];
        $columns = '';
        $params = '';

        foreach ($data as $key => $value) {
            $columns .= $key . ',';
            $params .= '?,';
            array_push($values, $data[$key]);
        }

        $columns = substr($columns, 0, -1);
        $params = substr($params, 0, -1);

        return [
            'sentence' => 'INSERT INTO ' . $table . '(' . $columns . ') VALUES(' . $params . ')',
            'values' => $values
        ];
    }
    public function insert($data, $token)
    {
        try {
            $sql = self::getSqlInsert($data, $this->tableName);

            $conection = $this->connect();

            $statement = $conection->prepare($sql['sentence']);

            $resultDecodeToken = self::decode($token);

            $conection->beginTransaction();

            $statement->execute($sql['values']);

            $lastInsertId = intval($conection->lastInsertId());

            $dataInserted = $data;
            $dataInserted['id'] = $lastInsertId;

            $newDataToLog = [
                'usuario' => $resultDecodeToken->data->username,
                'id_usuario' => $resultDecodeToken->data->id,
                'accion' => 'Registrar',
                'tabla' => $this->tableName,
                'datos' => json_encode($dataInserted)
            ];

            $sqlToLog = $this->getSqlInsert($newDataToLog, Config::$DB['log_table']);

            $statementToLog = $conection->prepare($sqlToLog['sentence']);

            $statementToLog->execute($sqlToLog['values']);

            $conection->commit();

            $message = 'Se ha insertado los datos correctamente.';
            return new Response(200, $message, $lastInsertId);
        } catch (PDOException $exception) {
            $conection->rollBack();

            $message = 'Ha sucedido un error al insertar los datos.';
            return new Response(400, $message, $exception);
        } catch (Exception $exception) {
            $message = 'Ha sucedido un error al validar el token.';
            return new Response(400, $message, $exception);
        }
    }
    public function insertNotToken($data)
    {
        try {
            $sql = self::getSqlInsert($data, $this->tableName);

            $conection = $this->connect();

            $statement = $conection->prepare($sql['sentence']);

            $statement->execute($sql['values']);

            $message = 'Se ha insertado los datos correctamente.';
            return new Response(200, $message, intval($conection->lastInsertId()));
        } catch (PDOException $exception) {
            $message = 'Ha sucedido un error al insertar los datos.';
            return new Response(400, $message, $exception);
        }
    }
    public function update($id, $data, $token)
    {
        $values = [];
        $columns = '';
        $params = '';

        foreach ($data as $key => $value) {
            $columns .= $key . ' = ?,';
            array_push($values, $data[$key]);
        }

        $columns = substr($columns, 0, -1);
        $params = substr($params, 0, -1);

        $sql = 'UPDATE ' . $this->tableName . ' SET ' . $columns . 'WHERE id = ?';

        array_push($values, $id);

        $conection = $this->connect();

        $statement = $conection->prepare($sql);

        try {
            $statement->execute($values);
            return $id;
        } catch (PDOException $exception) {
            return $exception;
        }
    }
}
