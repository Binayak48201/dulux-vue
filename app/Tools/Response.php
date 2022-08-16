<?php

namespace App\Tools;

class Response
{

    public static function prepare($error, $message, $data, array $meta)
    {
        return [
            'error' => $error,
            'message' => $message,
            'data' => $data,
            'meta' => $meta
        ];
    }
}
