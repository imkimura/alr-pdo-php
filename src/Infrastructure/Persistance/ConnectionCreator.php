<?php

namespace Alura\Pdo\Infrastructure\Persistance;

use PDO;

class ConnectionCreator
{
    public static function create(): PDO
    {
        $databasePath = __DIR__ . '/../../../database.sqlite';

        return new PDO("sqlite:{$databasePath}");
    }
}