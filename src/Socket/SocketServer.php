<?php

namespace App\Socket;



use PHPSocketIO\SocketIO;

class SocketServer
{
    private static $server;

    private function __construct()
    {
    }

    public static function getSocketInstance(): SocketIO
    {
        if (is_null(static::$server)) {
            static::$server = new SocketIO(4500);
        }
        return static::$server;
    }

}