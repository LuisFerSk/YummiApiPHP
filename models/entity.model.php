<?php

class Entity
{
    public function __construct()
    {
        $this->id = null;
        $this->create_time = null;
        $this->update_time = date('Y-m-d H:i:s', time());
    }

    public function setId($id)
    {
        if (!isset($id)) {
            return 'El id es obligatorio.';
        }
        if (!is_int(intval($id))) {
            return 'El id tiene que ser un número entero.';
        }
        $this->id = $id;
        return 'El id es correcto.';
    }
    public function setIdNoObligatorio($id)
    {
        if (isset($id) && !is_int(intval($id))) {
            return 'El id tiene que ser un número entero.';
        }
        $this->id = intval($id);
        return 'El id es correcto.';
    }
}
