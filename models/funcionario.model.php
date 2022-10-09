<?php

include_once 'entity.model.php';

class Funcionario extends Entity
{
    public function ____construct()
    {
        parent::__construct();

        $this->identificacion = null;
        $this->nombre = null;
        $this->sectorial = null;
        $this->subsector = null;
    }
    public function setIdentificacion($identificacion)
    {
        if (!isset($identificacion)) {
            return "La identificación es obligatoria.";
        }
        if (!is_string($identificacion)) {
            return "La identificación debe ser una cadena de caracteres.";
        }

        $identificacionSerialized = trim($identificacion);

        if (strlen($identificacionSerialized) < 1) {
            return 'La identificación no puede ser una cadena vacía o de espacios.';
        }
        if (preg_match("/^[0-9]*$/", $identificacion) === false) {
            return "La identificación solo puede contener números.";
        }

        $identificacionLength = strlen($identificacion);

        if ($identificacionLength < 8 || $identificacionLength > 11) {
            return 'La identificación debe estar entre 8 a 11 caracteres.';
        }
        $this->identificacion = $identificacion;
        return 'La identificación es correcta.';
    }
    public function setNombre($nombre)
    {
        if (!isset($nombre)) {
            return "El nombre es obligatorio.";
        }
        if (!is_string($nombre)) {
            return "El nombre debe ser una cadena de caracteres.";
        }

        $nombreSerialized = trim($nombre);

        $nombreSerializedLength = strlen($nombreSerialized);

        if ($nombreSerializedLength < 1) {
            return "El nombre no puede contener solo espacios.";
        }
        if ($nombreSerializedLength < 3 || $nombreSerializedLength > 50) {
            return "EL nombre debe ser una cadena de entre 3 y 100 caracteres.";
        }
        if (!preg_match("/^[a-zA-Z ]+$/", $nombreSerialized)) {
            return "El nombre solo puede contener letras.";
        }
        $this->nombre = $nombreSerialized;
        return 'El nombre es correcto.';
    }
    public function setSectorial($idSectorial)
    {
        $resultId = $this->setId($idSectorial);
        if ($resultId !== 'El id es correcto.') {
            return 'Verifique el sectorial: ' . $idSectorial;
        }
        $this->sectorial = $idSectorial;
        return 'El sectorial es correcto.';
    }
    public function setSubsector($idSubsector)
    {
        $resultId = $this->setIdNoObligatorio($idSubsector);
        if ($resultId !== 'El id es correcto.') {
            return 'Verifique el subsector: ' . $resultId;
        }
        $this->subsector = $idSubsector;
        return 'El subsector es correcto.';
    }

    public function setFuncionario($funcionario)
    {
        $resultSetIdentificacion = $this->setIdentificacion($funcionario['identificacion']);
        if ($resultSetIdentificacion != 'La identificación es correcta.') {
            return $resultSetIdentificacion;
        }
        $resultSetNombre = $this->setNombre($funcionario['nombre']);
        if ($resultSetNombre != 'El nombre es correcto.') {
            return $resultSetNombre;
        }
        $resultSetSectorial = $this->setSectorial($funcionario['sectorial']);
        if ($resultSetSectorial != 'El sectorial es correcto.') {
            return $resultSetSectorial;
        }
        $resultSetSubsector = $this->setSubsector($funcionario['subsector']);
        if ($resultSetSubsector != 'El subsector es correcto.') {
            return $resultSetSubsector;
        }

        return "El funcionario es correcto.";
    }
}
