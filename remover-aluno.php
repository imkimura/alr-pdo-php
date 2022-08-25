<?php

require_once 'vendor/autoload.php';

use Alura\Pdo\Infrastructure\Persistance\ConnectionCreator;

$pdo = ConnectionCreator::create();

$preparedStatement = $pdo->prepare('DELETE FROM students WHERE id = ?;');
$preparedStatement->bindValue(1, 5, PDO::PARAM_INT);
var_dump($preparedStatement->execute());
