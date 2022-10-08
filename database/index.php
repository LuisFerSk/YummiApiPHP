<?php

include_once 'config/index.php';

require_once "vendor/autoload.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class DataBase
{
    public function __construct()
    {
        $this->host = 'localhost';
        $this->database = "Yummi";
        $this->user = "root";
        $this->password = '12345678';
    }
    public function connect()
    {
        try {
            $link = new PDO(
                'mysql:host=' . $this->host . ';dbname=' . $this->database,
                $this->user,
                $this->password
            );

            $link->exec('set names uft8');

            $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            die("Error: " . $exception->getMessage());
        }

        return $link;
    }

    static public function encode($data)
    {
        $time = time();

        $token = [
            "iat" => $time,
            "exp" => $time + (60 * 60 * 24),
            "data" => [
                "id" => $data['id'],
                "username" => $data['username']
            ]
        ];

        $jwt = JWT::encode($token, Config::$SECRET, 'HS256');

        return $jwt;
    }

    static public function decode($token)
    {
        return JWT::decode($token, new Key(Config::$SECRET, 'HS256'));
    }
}
