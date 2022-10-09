<?php

include_once 'entity.model.php';

class Periferico extends Entity
{
    public function __construct()
    {
        parent::__construct();

        $this->tipo_dispositivo = null;
        $this->referenciaPeriferico = null;
        $this->numeroSerial = null;
        $this->estado = null;
        $this->observaciones = null;
    }
    public function setDispositivo($tipo_dispositivo)
    {
        if (!isset($tipo_dispositivo)) {
            return "Se debe especificar el tipo de dispositivo.";
        }
        if ($tipo_dispositivo !== 1 && $tipo_dispositivo !== 2 && $tipo_dispositivo !== 3 && $tipo_dispositivo !== 4) {
            return 'El estado no es valido, no se encuentra dentro las opciones permitidas (1-4).';
        }
        $this->tipo_dispositivo = $tipo_dispositivo;
        return 'El tipo de dispositivo es correcto.';
    }
    public function setReferencia($referenciaPeriferico)
    {
        if (!isset($referenciaPeriferico)) {
            return "Se debe especificar la referencia";
        }

        $referenciaPerifericoSerilized = trim($referenciaPeriferico);

        $referenciaPerifericoSerilizedLength = strlen($referenciaPerifericoSerilized);

        if ($referenciaPerifericoSerilizedLength < 1) {
            return 'La referencia del periférico no puede ser una cadena vacía o de espacios.';
        }
        if ($referenciaPerifericoSerilizedLength > 50) {
            return 'La referencia del periférico no puede tener más de 50 caracteres.';
        }
        $this->referenciaPeriferico = $referenciaPeriferico;
        return 'La referencia es correcto.';
    }
    public function setNumeroSerial($numeroSerial)
    {
        if (!isset($numeroSerial)) {
            return "Se debe especificar la referencia";
        }

        $numeroSerialSerialized = trim($numeroSerial);

        $numeroSerialSerializedLength = strlen($numeroSerialSerialized);

        if ($numeroSerialSerializedLength < 1) {
            return 'El número serial del periférico no puede ser una cadena vacía o de espacios.';
        }
        if ($numeroSerialSerializedLength > 50) {
            return 'El número serial de periférico no puede tener más de 50 caracteres.';
        }
        $this->numeroSerial = $numeroSerial;
        return 'La referencia es correcto.';
    }
    public function setEstado($estado)
    {
        if (!isset($estado)) {
            return "Se debe especificar el estado del equipo.";
        }
        if ($estado !== 1 && $estado !== 2) {
            return 'El estado no es valido, no se encuentra dentro las opciones permitidas (1-2).';
        }
        $this->estado = $estado;
        return 'El estado es correcto.';
    }
    public function setObservaciones($observaciones)
    {
        if (!isset($observaciones)) {
            if (is_string($observaciones)) {
                return 'La observación no es valida, debe ser una cadena de caracteres.';
            }

            $observacionesSerialized = trim($observaciones);

            $observacionesSerializedLength = strlen($observacionesSerialized);

            if ($observacionesSerializedLength < 1) {
                return 'La observación no puede ser una cadena vacía o de espacios.';
            }
            if ($observacionesSerializedLength > 100) {
                return 'La observación no puede tener más de 100 caracteres.';
            }
            $this->observaciones = $observacionesSerialized;
            return 'La observación es correcta.';
        }
        $this->observaciones = $observaciones;
        return 'La observación es correcta.';
    }
    public function setPeriferico($periferico)
    {
        $resultSetDispositivo = $this->setDispositivo(intval($periferico['tipo_dispositivo']));
        if ($resultSetDispositivo != 'El tipo de dispositivo es correcto.') {
            return $resultSetDispositivo;
        }

        $resultSetReferencia = $this->setReferencia($periferico['referenciaPeriferico']);
        if ($resultSetReferencia != 'La referencia es correcto.') {
            return $resultSetReferencia;
        }

        $resultSetNumeroSerial = $this->setNumeroSerial($periferico['numeroSerial']);
        if ($resultSetNumeroSerial != 'La referencia es correcto.') {
            return $resultSetNumeroSerial;
        }

        $resultSetEstado = $this->setEstado(intval($periferico['estado']));
        if ($resultSetEstado != 'El estado es correcto.') {
            return $resultSetEstado;
        }

        $resultSetObservaciones = $this->setObservaciones($periferico['observaciones']);
        if ($resultSetObservaciones != 'La observación es correcta.') {
            return $resultSetObservaciones;
        }

        return 'El periferico es correcto.';
    }
}
