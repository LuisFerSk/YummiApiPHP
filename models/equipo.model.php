<?php

include_once 'entity.model.php';
include_once 'utils/index.php';

class Equipo extends Entity
{
    public function __construct()
    {
        parent::__construct();

        $this->idEquipo = null;
        $this->tipo = null;
        $this->referencia = null;
        $this->numeroSerialCPU = null;
        $this->numeroSerialMonitor = null;
        $this->numeroSerialTeclado = null;
        $this->numeroSerialMouse = null;
        $this->direccionIP = null;
        $this->sistemaOperativo = null;
        $this->softwareInstalado = null;
        $this->tipoProcesador = null;
        $this->memoria = null;
        $this->discoDuro = null;
        $this->capacidad = null;
        $this->espacioUsado = null;
        $this->sectorial = null;
        $this->subsector = null;
    }

    public function setIdEquipo($idEquipo)
    {
        if (!isset($idEquipo)) {
            return 'El id del equipo es obligatorio.';
        }
        if (!is_string($idEquipo)) {
            return 'El id del equipo es incorrecto, debe ser una cadena de caracteres.';
        }

        $idEquipoSerialized = trim($idEquipo);

        if (strlen($idEquipoSerialized) < 8 || strlen($idEquipoSerialized) > 50) {
            return "El id del equipo debe contener entre 8 a 50 caracteres.";
        }
        $this->idEquipo = $idEquipoSerialized;
        return 'El id del equipo es correcto.';
    }
    public function setTipo($tipo)
    {
        if (!isset($tipo)) {
            return "El tipo de equipo es obligatorio.";
        }
        if (!is_string($tipo)) {
            return 'El tipo de equipo es incorrecto, debe ser una cadena de caracteres.';
        }

        $tipoSerialized = trim($tipo);

        if (strlen($tipoSerialized) < 8 || strlen($tipoSerialized) > 10) {
            return "El tipo del equipo debe contener entre 8 a 10 caracteres.";
        }
        $this->tipo = $tipoSerialized;
        return 'El tipo de equipo es correcto.';
    }
    public function setReferencia($referencia)
    {
        if (!isset($referencia)) {
            return "La referencia es obligatoria.";
        }
        if (!is_string($referencia)) {
            return 'La referencia es incorrecta, debe ser una cadena de caracteres.';
        }

        $referenciaSerialized = trim($referencia);

        if (strlen($referenciaSerialized) < 4 || strlen($referenciaSerialized) > 20) {
            return "La referencia del equipo debe contener entre 4 a 20 caracteres.";
        }
        $this->referencia = $referenciaSerialized;
        return 'La referencia del equipo es correcto.';
    }
    public function setNumeroSerialCPU($numeroSerialCPU)
    {
        if (!isset($numeroSerialCPU)) {
            return "El número serial del equipo es obligatorio.";
        }
        if (!is_string($numeroSerialCPU)) {
            return 'El número serial del equipo es incorrecto, debe ser una cadena de caracteres.';
        }

        $numeroSerialCPUSerialized = trim($numeroSerialCPU);

        if (strlen($numeroSerialCPUSerialized) < 5 || strlen($numeroSerialCPUSerialized) > 10) {
            return "El número serial del equipo debe contener entre 5 a 10 caracteres.";
        }
        $this->numeroSerialCPU = $numeroSerialCPUSerialized;
        return 'El número serial del equipo es correcto.';
    }
    public function setNumeroSerialMonitor($numeroSerialMonitor)
    {
        if (!isset($numeroSerialMonitor)) {
            return "El número serial del monitor es obligatorio.";
        }
        if (!is_string($numeroSerialMonitor)) {
            return 'El número serial del monitor es incorrecto, debe ser una cadena de caracteres.';
        }

        $numeroSerialMonitorSerialized = trim($numeroSerialMonitor);

        if (strlen($numeroSerialMonitorSerialized) < 5 || strlen($numeroSerialMonitorSerialized) > 10) {
            return "El número serial del monitor debe contener entre 5 a 10 caracteres";
        }
        $this->numeroSerialMonitor = $numeroSerialMonitorSerialized;
        return 'El número serial del monitor es correcto.';
    }
    public function setNumeroSerialTeclado($numeroSerialTeclado)
    {
        if (!isset($numeroSerialTeclado)) {
            return "El número serial del teclado es obligatorio.";
        }

        if (!is_string($numeroSerialTeclado)) {
            return 'El número serial del teclado es incorrecto, debe ser una cadena de caracteres.';
        }

        $numeroSerialTecladoSerialized = trim($numeroSerialTeclado);

        if (strlen($numeroSerialTecladoSerialized) < 5 || strlen($numeroSerialTecladoSerialized) > 10) {
            return "El número serial del teclado debe contener de 5 a 10 caracteres.";
        }
        $this->numeroSerialTeclado = $numeroSerialTecladoSerialized;
        return 'El número serial del teclado es correcto.';
    }
    public function setNumeroSerialMouse($numeroSerialMouse)
    {
        if (!isset($numeroSerialMouse)) {
            return "El número serial del mouse es obligatorio.";
        }
        if (!is_string($numeroSerialMouse)) {
            return 'El número serial del mouse es incorrecto, debe ser una cadena de caracteres.';
        }

        $numeroSerialMouseSerialized = trim($numeroSerialMouse);

        if (strlen($numeroSerialMouseSerialized) < 5 || strlen($numeroSerialMouseSerialized) > 10) {
            return "El número serial del mouse debe contener de 5 a 10 caracteres.";
        }
        $this->numeroSerialMouse = $numeroSerialMouseSerialized;
        return 'El número serial del mouse es correcto.';
    }
    public function setDireccionIp($direccionIP)
    {
        if (!isset($direccionIP)) {
            $this->direccionIP = '0.0.0.0';
            return 'La dirección IP es correcta.';
        }
        if (!is_string($direccionIP)) {
            return 'La dirección IP es incorrecta, debe ser una cadena de caracteres.';
        }

        $direccionIPSerialized = trim($direccionIP);

        if (strlen($direccionIPSerialized) < 1) {
            return "La dirección IP no puede contener solo espacios.";
        }

        if (preg_match(Util::$regexIPV4, $direccionIPSerialized) === false && preg_match(Util::$regexIPV6, $direccionIPSerialized) === false) {
            return "La dirección IP no cumple con el formato.";
        }
        $this->direccionIP = $direccionIPSerialized;
        return 'La dirección IP es correcta.';
    }
    public function setSistemaOperativo($sistemaOperativo)
    {
        if (!isset($sistemaOperativo)) {
            return "El sistema operativo es obligatorio.";
        }
        if (!is_string($sistemaOperativo)) {
            return 'El sistema operativo es incorrecto, debe ser una cadena de caracteres.';
        }

        $sistemaOperativoSerialized = trim($sistemaOperativo);

        if (strlen($sistemaOperativoSerialized) < 4 || strlen($sistemaOperativoSerialized) > 20) {
            return "El sistema operativo debe contener entre 4 a 20 caracteres.";
        }
        $this->sistemaOperativo = $sistemaOperativoSerialized;
        return 'El sistema operativo es correcto.';
    }
    public function setSoftwareInstalado($softwareInstalado)
    {
        if (!isset($sistemaOperativo)) {
            $this->softwareInstalado = $softwareInstalado;
            return 'Los software instalados son correctos.';
        }

        $softwareInstaladoSerialized = trim($softwareInstalado);

        if (strlen($softwareInstaladoSerialized) < 1 || strlen($softwareInstaladoSerialized) > 500) {
            return 'Los software instalados debe ser una cadena de caracteres de 1 a 500.';
        }

        $this->softwareInstalado = $softwareInstaladoSerialized;
        return 'Los software instalados son correctos.';
    }
    public function setTipoProcesador($tipoProcesador)
    {
        if (!isset($tipoProcesador)) {
            return "El tipo de procesador es obligatorio.";
        }
        if (!is_string($tipoProcesador)) {
            return 'El tipo de procesador es incorrecto, debe ser una cadena de caracteres.';
        }

        $tipoProcesadorSerialized = trim($tipoProcesador);

        if (strlen($tipoProcesadorSerialized) < 4 || strlen($tipoProcesadorSerialized) > 20) {
            return "El tipo de procesador debe contener entre 4 a 20 caracteres.";
        }
        $this->tipoProcesador = $tipoProcesadorSerialized;
        return 'El tipo de procesador es correcto.';
    }
    public function setMemoriaRAM($memoria)
    {
        if (!isset($memoria)) {
            return "Se debe especificar la cantidad de memoria RAM.";
        }
        if (!is_string($memoria)) {
            return 'La cantidad de memoria RAM es incorrecta, debe ser una cadena de caracteres.';
        }

        $memoriaSerialized = trim($memoria);

        if (strlen($memoriaSerialized) < 1) {
            return "La cantidad de memoria RAM no puede contener solo espacios.";
        }

        if (preg_match(Util::$regexAlmacenamiento, $memoriaSerialized) === false) {
            return "La cantidad de memoria RAM no cumple con el formato.";
        }
        $this->memoria = $memoriaSerialized;
        return 'La cantidad de memoria RAM es correcta.';
    }
    public function setDiscoDuro($discoDuro)
    {
        if (!isset($discoDuro)) {
            return "Se debe especificar el disco duro.";
        }
        if (!is_string($discoDuro)) {
            return 'El disco duro es incorrecto, debe ser una cadena de caracteres.';
        }

        $discoDuroSerialized = trim($discoDuro);

        if (strlen($discoDuroSerialized) < 7 || strlen($discoDuroSerialized) > 20) {
            return "El disco duro debe contener entre 7 a 20 caracteres  .";
        }
        $this->discoDuro = $discoDuroSerialized;
        return 'El disco duro es correcto.';
    }
    public function setCapacidadDiscoDuro($capacidad)
    {
        if (!isset($capacidad)) {
            return "Se debe especificar la capacidad del disco duro.";
        }
        if (!is_string($capacidad)) {
            return 'La capacidad del disco duro es incorrecta, debe ser una cadena de caracteres.';
        }

        $capacidadSerialized = trim($capacidad);

        if (strlen($capacidadSerialized) < 1) {
            return "La capacidad del disco duro no puede contener solo espacios.";
        }

        if (preg_match(Util::$regexAlmacenamiento, $capacidadSerialized) === false) {
            return "La capacidad del disco duro no cumple con el formato.";
        }
        $this->capacidad = $capacidadSerialized;
        return 'La capacidad del disco duro es correcta.';
    }
    public function setEspacioUsado($espacioUsado)
    {
        if (!isset($espacioUsado)) {
            return "Se debe especificar el espacio usado del disco duro.";
        }
        if (!is_string($espacioUsado)) {
            return 'El espacio usado del disco duro es incorrecto, debe ser una cadena de caracteres.';
        }

        $espacioUsadoSerialized = trim($espacioUsado);

        if (strlen($espacioUsadoSerialized) < 1) {
            return "El espacio usado del disco duro es incorrecto, debe ser una cadena de caracteres.";
        }

        if (preg_match(Util::$regexAlmacenamiento, $espacioUsadoSerialized) === false) {
            return 'El valor ${espacioUsadoSerialized} para espacio usado del disco duro no cumple con el formato.';
        }
        $this->espacioUsado = $espacioUsadoSerialized;
        return 'El espacio usado del disco duro es correcto.';
    }
    public function setSectorial($idSectorial)
    {
        $resultId = $this->setId($idSectorial);
        if ($resultId != 'El id es correcto.') {
            return 'Verifique el sectorial: ' . $resultId;
        }
        $this->sectorial = $idSectorial;
        return 'El sectorial es correcto.';
    }
    public function setSubsector($subsector)
    {
        $resultId = $this->setIdNoObligatorio($subsector);
        if ($resultId != 'El id es correcto.') {
            return 'Verifique el subsector: ' . $resultId;
        }

        $this->subsector = $subsector;
        return 'El subsector es correcto.';
    }

    public function set($equipo)
    {
        $resultSetIdEquipo = $this->setIdEquipo($equipo['idEquipo']);
        if ($resultSetIdEquipo != 'El id del equipo es correcto.') {
            return $resultSetIdEquipo;
        }

        $resultSetTipo = $this->setTipo($equipo['tipo']);
        if ($resultSetTipo != 'El tipo de equipo es correcto.') {
            return $resultSetTipo;
        }

        $resultSetReferencia = $this->setReferencia($equipo['referencia']);
        if ($resultSetReferencia != 'La referencia del equipo es correcto.') {
            return $resultSetReferencia;
        }

        $resultSetNumeroSerialCPU = $this->setNumeroSerialCPU($equipo['numeroSerialCPU']);
        if ($resultSetNumeroSerialCPU != 'El número serial del equipo es correcto.') {
            return $resultSetNumeroSerialCPU;
        }

        $resultSetNumeroSerialMonitor = $this->setNumeroSerialMonitor($equipo['numeroSerialMonitor']);
        if ($resultSetNumeroSerialMonitor != 'El número serial del monitor es correcto.') {
            return $resultSetNumeroSerialMonitor;
        }

        $resultSetNumeroSerialTeclado = $this->setNumeroSerialTeclado($equipo['numeroSerialTeclado']);
        if ($resultSetNumeroSerialTeclado != 'El número serial del teclado es correcto.') {
            return $resultSetNumeroSerialTeclado;
        }

        $resultSetNumeroSerialMouse = $this->setNumeroSerialMouse($equipo['numeroSerialMouse']);
        if ($resultSetNumeroSerialMouse != 'El número serial del mouse es correcto.') {
            return $resultSetNumeroSerialMouse;
        }

        $resultSetDireccionIp = $this->setDireccionIp($equipo['direccionIP']);
        if ($resultSetDireccionIp != 'La dirección IP es correcta.') {
            return $resultSetDireccionIp;
        }

        $resultSetSistemaOperativo = $this->setSistemaOperativo($equipo['sistemaOperativo']);
        if ($resultSetSistemaOperativo != 'El sistema operativo es correcto.') {
            return $resultSetSistemaOperativo;
        }

        $resultSetTipoProcesador = $this->setTipoProcesador($equipo['tipoProcesador']);
        if ($resultSetTipoProcesador != 'El tipo de procesador es correcto.') {
            return $resultSetTipoProcesador;
        }

        $resultSetMemoriaRAM = $this->setMemoriaRAM($equipo['memoria']);
        if ($resultSetMemoriaRAM != 'La cantidad de memoria RAM es correcta.') {
            return $resultSetMemoriaRAM;
        }

        $resultSetDiscoDuro = $this->setDiscoDuro($equipo['discoDuro']);
        if ($resultSetDiscoDuro != 'El disco duro es correcto.') {
            return $resultSetDiscoDuro;
        }

        $resultSetSoftwareInstalado = $this->setSoftwareInstalado($equipo['softwareInstalado']);
        if ($resultSetSoftwareInstalado != "Los software instalados son correctos.") {
            return $resultSetSoftwareInstalado;
        }

        $resultSetCapacidadDiscoDuro = $this->setCapacidadDiscoDuro($equipo['capacidad']);
        if ($resultSetCapacidadDiscoDuro != 'La capacidad del disco duro es correcta.') {
            return $resultSetCapacidadDiscoDuro;
        }

        $resultSetEspacioUsado = $this->setEspacioUsado($equipo['espacioUsado']);
        if ($resultSetEspacioUsado != 'El espacio usado del disco duro es correcto.') {
            return $resultSetEspacioUsado;
        }

        $resultSetSectorial = $this->setSectorial($equipo['sectorial']);
        if ($resultSetSectorial != 'El sectorial es correcto.') {
            return  $resultSetSectorial;
        }

        $resultSetSubsector = $this->setSubsector($equipo['subsector']);
        if ($resultSetSubsector != 'El subsector es correcto.') {
            return $resultSetSubsector;
        }

        return "El equipo es correcto.";
    }
}
