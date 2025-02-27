<?php

namespace App\Common;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;

class Database
{
    private static $connection = null;
    private static $queryBuilder = null;

    public static function getConnection()
    {
        if (self::$connection === null) {
            $connectionParams = [
                'dbname'   => $_ENV['DB_NAME'],
                'user'     => $_ENV['DB_USER'],
                'password' => $_ENV['DB_PASSWORD'],
                'host'     => $_ENV['DB_HOST'],
                'driver'   => $_ENV['DB_DRIVE'],
                'port'     => $_ENV['DB_PORT'],
            ];

            try {
                self::$connection = DriverManager::getConnection($connectionParams);
                self::$queryBuilder = self::$connection->createQueryBuilder();
            } catch (Exception $e) {
                die('Lỗi kết nối: ' . $e->getMessage());
            }
        }

        return self::$connection;
    }

    public static function getQueryBuilder()
    {
        return self::$queryBuilder;
    }
}
