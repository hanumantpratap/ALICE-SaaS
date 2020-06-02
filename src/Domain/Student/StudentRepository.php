<?php
declare(strict_types=1);

namespace App\Domain\Student;

interface StudentRepository
{
    /**
     * @return Student[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return Student
     */
    public function findStudentOfId(int $id): Student;
}
