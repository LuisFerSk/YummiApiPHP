<?php

require_once "vendor/autoload.php";

use Firebase\JWT\{JWT, Key};

class DataBase
{
    public function __construct()
    {
        $this->host = 'localhost:3306';
        $this->database = "valledup_itecnologico";
        $this->user = "valledup";
        $this->password = 'BDfvGyX2TSXEvyfa2L';
    }
    public function connect()
    {
        try {
            $link = new PDO(
                'mysql:host=' . $this->host . ';dbname=' . $this->database. ';charset=utf8',
                $this->user,
                $this->password
            );
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
