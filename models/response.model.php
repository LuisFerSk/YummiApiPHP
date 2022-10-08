<?php

class Response
{
    public function __construct($status, $message, $data = null)
    {
        $this->status = $status;
        $this->message = $message;
        $this->data = $data;
    }

    static public function sendResponse($response)
    {
        $json = [
            "message" => $response->message,
            "data" => $response->data
        ];
        echo json_encode($json, http_response_code($response->status));
    }
}
