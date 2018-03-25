<?php

namespace core;

class DBConnector
{
    // private static $instance;

    // public static function getConnect()
    // {
    //     if(self::$instance === null) {
    //         self::$instance = self::getPDO();
    //     }

    //     return self::$instance;
    // }

    public static function getPDO()
    {
        $dsn = sprintf('%s:host=%s;dbname=%s', 'mysql', 'localhost', 'posts');
        return new \PDO($dsn, 'root', 'root');
    }
}