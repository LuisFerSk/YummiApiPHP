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
            $message = "Se ha ejecutado la sentencia correctamente.";
            return new Response(200, $message, $statement);
        } catch (PDOException $exception) {
            $message = "Ha ocurrido un error al ejecutar la sentencia.";
            return new Response(400, $message, $exception);
        }
    }

    public function getAll()
    {
        $sql = 'SELECT * FROM ' . $this->tableName;

        $conection = $this->connect();

        $statement = $conection->prepare($sql);

        try {
            $statement->execute();
            $message = 'Se ha obtenido la información correctamente.';
            return new Response(200, $message, $statement->fetchAll(PDO::FETCH_CLASS));
        } catch (PDOException $exception) {
            $message = "Ha ocurrido un error al obtenido la información.";
            return new Response(400, $message, $exception);
        }
    }

    public function getBy($column, $value)
    {
        $sql = 'SELECT * FROM ' . $this->tableName . ' WHERE ' . $column . ' = ?';

        $conection = $this->connect();

        $statement = $conection->prepare($sql);

        try {
            $statement->execute([$value]);
            $message = 'Se ha obtenido la información correctamente.';
            return new Response(200, $message, $statement->fetchAll(PDO::FETCH_CLASS));
        } catch (PDOException $exception) {
            $message = 'No se ha podido obtener la información.';
            return new Response(400, $message, $exception);
        }
    }

    public function getById($id)
    {
        $result = $this->getBy('id', $id);

        if ($result->status != 200) {
            return $result;
        }

        return new Response($result->status, $result->message, $result->data[0]);
    }

    public function count()
    {
        $sql = 'SELECT COUNT(*) as count FROM ' . $this->tableName;

        $conection = $this->connect();

        $statement = $conection->prepare($sql);

        try {
            $statement->execute();
            $message = "Se ha obtenido el número de registros correctamente.";
            return new Response(200, $message, $statement->fetchAll(PDO::FETCH_CLASS)[0]);
        } catch (PDOException $exception) {
            $message = "Ha sucedido un error al obtener el número de registros.";
            return new Response(400, $message, $exception);
        }
    }

    public function delete($id, $token)
    {
        $sql = 'DELETE FROM ' . $this->tableName . ' WHERE id = ?';

        $conection = $this->connect();

        $statement = $conection->prepare($sql);

        $conection->beginTransaction();

        try {
            $statement->execute([$id]);
        } catch (PDOException $exception) {
            $message = 'No se ha podido eliminar el registro.';
            return new Response(400, $message, $exception);
        }

        if ($statement->rowCount() < 1) {
            $message = 'El registro no existe.';
            return new Response(404, $message);
        }

        $accion = 'Eliminar';
        $resultGetDataToLog = $this->getDataToLog($token, $accion, ['id' => $id]);

        if ($resultGetDataToLog->status != 200) {
            return $resultGetDataToLog;
        }

        $sqlToLog = $this->getSqlInsert($resultGetDataToLog->data, Config::$DB['log_table']);

        $statementToLog = $conection->prepare($sqlToLog['sentence']);

        try {
            $statementToLog->execute($sqlToLog['values']);
        } catch (PDOException $exception) {
            $conection->rollBack();

            $message = 'Ha sucedido un error al insertar los datos dentro del registro log.';
            return new Response(400, $message, $exception);
        }

        $conection->commit();

        $message = 'Se ha eliminado correctamente el registro.';
        return new Response(200, $message, $statement->rowCount());
    }

    public function deleteNoToken($id)
    {
        $sql = 'DELETE FROM ' . $this->tableName . ' WHERE id = ?';

        $conection = $this->connect();

        $statement = $conection->prepare($sql);

        try {
            $statement->execute([$id]);
        } catch (PDOException $exception) {
            $message = 'No se ha podido eliminar el registro.';
            return new Response(400, $message, $exception);
        }

        if ($statement->rowCount() < 1) {
            $message = 'El registro no existe.';
            return new Response(404, $message);
        }

        $message = 'Se ha eliminado correctamente el registro.';
        return new Response(200, $message, $statement->rowCount());
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
        $sql = self::getSqlInsert($data, $this->tableName);

        $conection = $this->connect();

        $statement = $conection->prepare($sql['sentence']);

        $conection->beginTransaction();

        try {
            $statement->execute($sql['values']);
        } catch (PDOException $exception) {
            $conection->rollBack();

            $message = 'Ha sucedido un error al insertar los datos.';

            if ($exception->errorInfo[1] == 1062) {
                return new Response(409, $message, $exception);
            }

            return new Response(400, $message, $exception);
        }

        $lastInsertId = intval($conection->lastInsertId());

        $dataInserted = $data;
        $dataInserted['id'] = $lastInsertId;

        $accion = 'Registrar';
        $resultGetDataToLog = $this->getDataToLog($token, $accion, $dataInserted);

        if ($resultGetDataToLog->status != 200) {
            return $resultGetDataToLog;
        }

        $sqlToLog = $this->getSqlInsert($resultGetDataToLog->data, Config::$DB['log_table']);

        $statementToLog = $conection->prepare($sqlToLog['sentence']);

        try {
            $statementToLog->execute($sqlToLog['values']);
        } catch (PDOException $exception) {
            $conection->rollBack();

            $message = 'Ha sucedido un error al insertar los datos dentro del registro log.';
            return new Response(400, $message, $exception);
        }

        $conection->commit();

        $message = 'Se ha insertado los datos correctamente.';
        return new Response(200, $message, $lastInsertId);
    }

    public function insertNotToken($data)
    {
        $sql = self::getSqlInsert($data, $this->tableName);

        $conection = $this->connect();

        $statement = $conection->prepare($sql['sentence']);

        try {
            $statement->execute($sql['values']);
        } catch (PDOException $exception) {
            $message = 'Ha sucedido un error al insertar los datos.';
            return new Response(400, $message, $exception);
        }

        $message = 'Se ha insertado los datos correctamente.';
        return new Response(200, $message, intval($conection->lastInsertId()));
    }

    static private function getSqlUpdate($id, $data, $table)
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

        array_push($values, $id);

        return [
            'sentence' => 'UPDATE ' . $table . ' SET ' . $columns . ' WHERE id = ?',
            'values' => $values
        ];
    }

    public function update($id, $data, $token)
    {
        $sql = self::getSqlUpdate($id, $data, $this->tableName);

        $conection = $this->connect();

        $statement = $conection->prepare($sql['sentence']);

        $conection->beginTransaction();

        try {
            $statement->execute($sql['values']);
        } catch (PDOException $exception) {
            $conection->rollBack();

            $message = "Ha sucedido un error al actualizar los datos.";
            return new Response(400, $message, $exception);
        }

        if ($statement->rowCount() < 1) {
            $message = "No se encontró el registro.";
            return new Response(404, $message);
        }

        $dataInserted = $data;
        $dataInserted['id'] = $id;

        $accion = 'Actualizar';

        $resultGetDataToLog = $this->getDataToLog($token, $accion, $dataInserted);

        if ($resultGetDataToLog->status != 200) {
            return $resultGetDataToLog;
        }

        $sqlToLog = $this->getSqlInsert($resultGetDataToLog->data, Config::$DB['log_table']);

        $statementToLog = $conection->prepare($sqlToLog['sentence']);

        try {
            $statementToLog->execute($sqlToLog['values']);
        } catch (PDOException $exception) {
            $conection->rollBack();

            $message = 'Ha sucedido un error al actualizar los datos dentro del registro log.';
            return new Response(400, $message, $exception);
        }

        $conection->commit();

        $message = 'Se han actualizado los datos correctamente.';
        return new Response(200, $message, $id);
    }

    public function updateNoToken($id, $data)
    {
        $sql = self::getSqlUpdate($id, $data, $this->tableName);

        $conection = $this->connect();

        $statement = $conection->prepare($sql['sentence']);

        try {
            $statement->execute($sql['values']);
        } catch (PDOException $exception) {
            $message = 'Ha sucedido un error al insertar los datos.';
            return new Response(400, $message, $exception);
        }

        if ($statement->rowCount() < 1) {
            $message = "No se encontró el registro.";
            return new Response(404, $message);
        }

        $message = 'Se ha insertado los datos correctamente.';
        return new Response(200, $message, $id);
    }
    public function getDataToLog($token, $accion, $dataInserted)
    {
        $resultDecodeToken = $this->validarToken($token);

        if ($resultDecodeToken->status != 200) {
            return $resultDecodeToken;
        }

        $dataToLog = [
            'usuario' => $resultDecodeToken->data->username,
            'id_usuario' => $resultDecodeToken->data->id,
            'accion' => $accion,
            'tabla' => $this->tableName,
            'datos' => json_encode($dataInserted)
        ];

        $message = 'Se ha generado la data para el registro log correctamente.';
        return new Response(200, $message, $dataToLog);
    }
    public function validarToken($token)
    {
        try {
            $resultDecodeToken = self::decode($token);
        } catch (Exception $exception) {
            $message = 'Ha sucedido un error al validar el token.';
            return new Response(400, $message, $exception);
        }

        if ($resultDecodeToken->exp <= time()) {
            return new Response(400, 'El token ya expiro.');
        }

        $sql = 'SELECT id, username, rol FROM ' . Config::$DB['usuario_table'] . ' WHERE id = ?';

        $result = $this->execute($sql, [$resultDecodeToken->data->id]);
        if ($result->status != 200) {
            $message = 'No se ha podido obtener la información del token: ' . strtolower($result->message);
            return new Response($result->status, $message, $result->data);
        }


        $message = 'El token es correcto';
        return new Response($result->status, $message, $result->data->fetchAll(PDO::FETCH_CLASS)[0]);
    }
}
