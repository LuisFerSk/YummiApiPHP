<?php

class UsuarioController
{
    public function __construct($dbController, $usuarioModel = null)
    {
        $this->dbController = $dbController;
        $this->usuarioModel = $usuarioModel;
    }

    public function getAll()
    {
        $sql = 'SELECT usuarios.id, usuarios.username,usuarios.rol, estados.id AS id_estado, estados.nombre AS estado, usuarios.create_time, usuarios.update_time FROM usuarios LEFT JOIN estados ON estados.id = usuarios.estado WHERE rol = 2';

        try {
            $data = $this->dbController->execute($sql)->fetchAll(PDO::FETCH_CLASS);
            $message = 'Los usuarios se han obtenido correctamente.';
            return new Response(200, $message, $data);
        } catch (Exception $error) {
            $message = 'Ha sucedido un error al obtener los equipos.';
            return new Response(400, $message, $error);
        }
    }

    public function getById($id)
    {
        $sql = 'SELECT usuarios.id, usuarios.username, usuarios.rol, estados.id AS id_estado, estados.nombre AS estado, usuarios.create_time, usuarios.update_time FROM usuarios LEFT JOIN estados ON estados.id = usuarios.estado WHERE usuarios.id = ?';

        try {
            $result = $this->dbController->execute($sql, [$id]);
        } catch (PDOException $exception) {
            $message = 'Ha sucedido un error al obtener el usuario.';
            return new Response(400, $message, $exception);
        }

        $message = 'El usuarioF se han obtenido correctamente.';
        return new Response(200, $message, $result->fetchAll(PDO::FETCH_CLASS)[0]);
    }

    public function getAdmin()
    {
        try {
            $result = $this->dbController->getBy('rol', 1);
            $message = 'Los usuarios se han obtenido correctamente.';
            return new Response(200, $message, $result);
        } catch (PDOException $exception) {
            $message = 'Ha sucedido un error al obtener los usuarios.';
            return new Response(400, $message, $exception);
        }
    }

    public function insert($usuario)
    {
        $resultSetUsername = $this->usuarioModel->setUsername($usuario['username']);
        if ($resultSetUsername != 'El username es correcto.') {
            return new Response(400, $resultSetUsername);
        }

        $resultSetPassword = $this->usuarioModel->setPassword($usuario['password']);
        if ($resultSetPassword != 'La contraseña es correcta.') {
            return new Response(400, $resultSetPassword);
        }

        $dataToInsert = [
            "username" => $this->usuarioModel->username,
            "password" => $this->usuarioModel->password
        ];

        $result = $this->dbController->insertNotToken($dataToInsert);

        if ($result->status == 200) {
            $get = $this->dbController->getById($result->data);

            $message = 'Se ha registrado al usuario correctamente.';

            if ($get->status == 200) {
                return new Response(200, $message, $get->data);
            }

            return new Response(200, $message);
        }

        $message = 'No se ha podido registrado al usuario.';
        return new Response($result->status, $message, $result->data);
    }
    public function insertAdmin()
    {
        $result = $this->usuarioModel->setPassword('admin12345678');

        $dataToInsert = [
            "id" => 1,
            "username" => 'admin',
            "password" => $this->usuarioModel->password,
            "rol" => 1
        ];

        $result = $this->dbController->insertNotToken($dataToInsert);

        if ($result->status == 200) {
            $message = 'Se ha restablecido correctamente el usuario administrador.';
            return new Response(200, $message);
        }

        $message = 'No se ha podido restablecer el usuario administrador.';
        return new Response($result->status, $message, $result->data);
    }

    public function updatePassword($id, $password)
    {
        $resultSetId = $this->usuarioModel->setId($id);
        if ($resultSetId != 'El id es correcto.') {
            return new Response(400, $resultSetId);
        }

        $resultSetPassword = $this->usuarioModel->setPassword($password);
        if ($resultSetPassword != 'La contraseña es correcta.') {
            return new Response(400, $resultSetPassword);
        }

        $dataToUpdate = [
            "password" => $this->usuarioModel->password,
            "update_time" => $this->usuarioModel->update_time
        ];

        $result = $this->dbController->updateNoToken($id, $dataToUpdate);
        if ($result->status == 404) {
            $message = 'No se ha encontrado al usuario.';
            return new Response($result->status, $message);
        }

        if ($result->status != 200) {
            $message = 'No se ha podido actualizar la contraseña.';
            return new Response($result->status, $message, $result->data);
        }

        $message = 'Se ha actualizado la contraseña correctamente.';
        return new Response(200, $message);
    }

    public function login($usuario)
    {
        $username = $usuario['username'];
        $password = $usuario['password'];

        $resultGetUser = $this->dbController->getBy('username', $username);
        if ($resultGetUser->status == 200) {
            if (count($resultGetUser->data) == 0) {
                $message = 'El usuario no existe.';
                return new Response(400, $message);
            }

            $user = $resultGetUser->data[0];

            if ($user->estado == Config::$ESTADOS['DESHABILIADO']['id']) {
                $message = 'Este usuario esta deshabilitado, comuníquese con el administrador.';
                return new Response(400, $message);
            }

            if (password_verify($password, $user->password)) {
                $data = [
                    'id' => $user->id,
                    'username' => $user->username
                ];

                $token = DbController::encode($data);

                $message = 'El usuario ha iniciado sesión correctamente.';
                return new Response(200, $message, ['token' => $token]);
            }

            $message = 'La contraseña no coincide.';
            return new Response(400, $message);
        }

        $message = 'Ha sucedido un error al iniciar sesión.';
        return new Response($resultGetUser->status, $message, $resultGetUser->data);
    }
    public function delete($id)
    {
        $resultSetId = $this->usuarioModel->setId($id);
        if ($resultSetId !== 'El id es correcto.') {
            return new Response(400, $resultSetId);
        }

        $result = $this->dbController->deleteNoToken($this->usuarioModel->id);

        if ($result->status == 404) {
            $message = 'El usuario no existe.';
            return new Response($result->status, $message);
        }

        if ($result->status != 200) {
            $message = 'No se ha podido eliminar el usuario.';
            return new Response($result->status, $message, $result->data);
        }

        $message = 'Se ha eliminado el usuario correctamente.';
        return new Response(200, $message);
    }
    public function disable($id)
    {
        $resultSetId = $this->usuarioModel->setId($id);
        if ($resultSetId != 'El id es correcto.') {
            return new Response(400, $resultSetId);
        }

        $dataToUpdate = [
            "estado" => Config::$ESTADOS['DESHABILIADO']['id'],
            "update_time" => $this->usuarioModel->update_time
        ];

        $result = $this->dbController->updateNoToken($this->usuarioModel->id, $dataToUpdate);

        if ($result->status == 404) {
            $message = 'El usuario no existe.';
            return new Response($result->status, $message);
        }

        if ($result->status != 200) {
            $message = 'No se ha podido deshabilitar al usuario.';
            return new Response($result->status, $message, $result->data);
        }

        $message = 'Se ha deshabilitado al usuario correctamente.';
        return new Response(200, $message);
    }
    public function enable($id)
    {
        $resultSetId = $this->usuarioModel->setId($id);
        if ($resultSetId != 'El id es correcto.') {
            return new Response(400, $resultSetId);
        }

        $dataToUpdate = [
            "estado" => Config::$ESTADOS['HABILITADO']['id'],
            "update_time" => $this->usuarioModel->update_time
        ];

        $result = $this->dbController->updateNoToken($this->usuarioModel->id, $dataToUpdate);

        if ($result->status == 404) {
            $message = 'El usuario no existe.';
            return new Response($result->status, $message);
        }

        if ($result->status != 200) {
            $message = 'No se ha podido habilitar al usuario.';
            return new Response($result->status, $message, $result->data);
        }

        $message = 'Se ha habilitado al usuario correctamente.';
        return new Response(200, $message);
    }
    public function restoreAdmin()
    {
    }
}
