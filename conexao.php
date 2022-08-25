<?php

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Infrastructure\Persistance\ConnectionCreator;

require_once 'vendor/autoload.php';

$pdo = ConnectionCreator::create();

echo "Conectei \n";

$students = "CREATE TABLE IF NOT EXISTS students(
    id INTEGER PRIMARY KEY,
    name TEXT,
    birth_date TEXT
);";

$phones = "CREATE TABLE IF NOT EXISTS phones(
    id INTEGER PRIMARY KEY,
    area_code TEXT,
    number TEXT,
    student_id INTEGER,
    FOREIGN KEY (student_id) REFERENCES students (id)
);";


$sql = "
    {$students}

    {$phones}
";

$pdo->exec($sql);