<?php

class Config
{
    static public $DB = [
        "equipo_table" => 'equipos',
        "funcionario_table" => 'funcionarios',
        "periferico_table" => 'perifericos',
        "usuario_table" => 'usuarios',
        "sectorial_table" => 'sectorial',
        "subsector_table" => 'subsector',
        "tipo_dispositivo_table" => 'tipo_dispositivos',
        "log_table" => 'log_controller'
    ];

    static public $ESTADOS = [
        "OPERATIVO" => [
            "id" => 1,
            "nombre" => 'Operativo'
        ],
        "NO_OPERATIVO" => [
            "id" => 2,
            "nombre" => 'No operativo'
        ],
        "HABILITADO" => [
            "id" => 3,
            "nombre" => 'Habilitado'
        ],
        "DESHABILIADO" => [
            "id" => 4,
            "nombre" => 'Deshabilitado'
        ]
    ];

    static public $SECRET = '6d05954d-ec64-4519-a221-8fa96764fa6e';
}
