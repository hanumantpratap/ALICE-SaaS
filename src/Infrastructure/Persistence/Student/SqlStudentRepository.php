<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Student;

use App\Exceptions;
use App\Domain\Student\Student;
use App\Domain\Student\StudentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Psr\Log\LoggerInterface;

final class SqlStudentRepository implements StudentRepository
{
    private LoggerInterface $logger;
    private EntityManagerInterface $entityManager;
    private ObjectRepository $repository;

    public function __construct(LoggerInterface $logger, EntityManagerInterface $entityManager)
    {
      $this->logger = $logger;
      $this->entityManager = $entityManager;
      $this->repository = $entityManager->getRepository(Student::class);
    }

    function findAll(): array {
        return $this->repository->findAll();
    }

    public function findStudentOfId(int $id): Student {
      /** @var Student $student */
      $student = $this->repository->findOneBy(['id' => $id]);

      if (!is_null($student)) {
          return $student;
      }

      throw new Exceptions\NotFoundException('The Student you requested does not exist.');
    }
}