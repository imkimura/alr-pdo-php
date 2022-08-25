<?php

namespace Alura\Pdo\Infrastructure\Repository;

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Domain\Repository\StudentRepository;

class PDOStudentRepository implements StudentRepository
{
    private \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function allStudents(): array
    {
        $stmt = $this->connection->query('SELECT * FROM students;');

        return $this->hydrateStudentList($stmt);
    }

    public function studentsBirthAt(\DateTimeInterface $birthDate): array
    {
        $sql = 'SELECT * FROM students WHERE birth_date = ?;';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(1, $birthDate->format('Y-m-d'));
        $stmt->execute();

        return $this->hydrateStudentList($stmt);
    }

    public function save(Student $student): bool
    {
        if ($student->id() === null) {
            return $this->insert($student);
        }

        return $this->update($student);
    }

    private function insert(Student $student): bool
    {
        $sql = 'INSERT INTO students (name, birth_date) VALUES (:name, :birth_date);';

        $stmt = $this->connection->prepare($sql);

        $success = $stmt->execute([
            ':name' => $student->name(),
            ':birth_date' => $student->birthDate()->format('Y-m-d'),
        ]);

        $student->defineId($this->connection->lastInsertId());

        return $success;
    }

    private function update(Student $student): bool
    {
        $sql = 'UPDATE students
                SET name = :name,
                birth_date = :birth_date
                WHERE id = :id;';

        $stmt = $this->connection->prepare($sql);

        return $stmt->execute([
            ':name' => $student->name(),
            ':birth_date' => $student->birthDate()->format('Y-m-d'),
            ':id' => $student->id()
        ]);
    }

    public function delete(Student $student): bool
    {
        $stmt = $this->connection->prepare('DELETE FROM students WHERE id = ?;');
        $stmt->bindValue(1, $student->id(), \PDO::PARAM_INT);

        return $stmt->execute();
    }

    private function hydrateStudentList(\PDOStatement $stmt): array
    {
        $studentList = [];
        $studentDataList = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($studentDataList as $studentData) {
            $studentList[] = new Student(
                $studentData['id'],
                $studentData['name'],
                new \DateTimeImmutable($studentData['birth_date'])
            );
        }

        return $studentList;
    }
}