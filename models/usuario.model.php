<?php

include_once 'entity.model.php';

class Usuario extends Entity
{
    public function ____construct()
    {
        parent::__construct();

        $this->username = null;
        $this->password = null;
        $this->rol = null;
        $this->estado = null;
    }
    public function setUsername($username)
    {
        if (!isset($username)) {
            return "Se debe especificar el username.";
        }
        if (!is_string($username)) {
            return 'El username es incorrecto, debe ser una cadena de caracteres.';
        }

        $usernameSerialized = trim($username);

        $usernameSerializedLength = strlen($usernameSerialized);

        if ($usernameSerializedLength < 1) {
            return "El username no puede contener solo espacios.";
        }
        if ($usernameSerializedLength < 5 || $usernameSerializedLength > 20) {
            return "El username debe ser una cadena de entre 5 y 50 caracteres.";
        }
        $this->username = $usernameSerialized;
        return 'El username es correcto.';
    }
    public function setPassword($password)
    {

        if (!isset($password)) {
            return "Se debe especificar la contraseña.";
        }
        if (!is_string($password)) {
            return 'La contraseña es incorrecto, debe ser una cadena de caracteres.';
        }

        $passwordSerialized = trim($password);

        $passwordSerializedLength = strlen($passwordSerialized);

        if ($passwordSerializedLength < 1) {
            return "La contraseña no puede contener solo espacios.";
        }
        if ($passwordSerializedLength < 8 || $passwordSerializedLength > 50) {
            return "La contraseña debe ser una cadena de entre 8 y 50 caracteres.";
        }

        $this->hashPassword($passwordSerialized);
        return 'La contraseña es correcta.';
    }
    private function hashPassword($password)
    {
        if (!is_string($password)) {
            return 'La contraseña debe ser una cadena de caracteres';
        }

        $options = [
            'cost' => 12,
        ];

        $this->password = password_hash($password, PASSWORD_BCRYPT, $options);
        return 'Se ha encriptado la contraseña correctamente';
    }
}
