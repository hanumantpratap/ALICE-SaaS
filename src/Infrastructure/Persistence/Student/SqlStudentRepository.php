<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Student;

use App\Exceptions;
use App\Domain\Student\Student;
use App\Domain\Student\StudentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ObjectRepository;
use Psr\Log\LoggerInterface;
use InvalidArgumentException;

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





    // Find students based on field searches in the students table.
    // All search criteria in array, $params, will be concatenated by ANDs.
    public function findStudentsByParams(array $params): array {

      // declare the QueryBuilder
      $qb = $this->entityManager->createQueryBuilder("s")
          ->from(Student::class, "s")->select("s");

      // iterate through query params, and build the query
      foreach( $params as $key => $value ) {
          switch( $key ) {
              // a partial string match in a Student field
              case "firstName": case "lastName": case "middleInitial":  case "suffix":
                  $criteria = Criteria::create()->where(Criteria::expr()->contains( $key, $value ));
                  $qb->addCriteria( $criteria );
                  break;
               // an exact match in a Student field
               case "gender": case "dob": case "grade": case "inactive":
                  $criteria = Criteria::create()->where(Criteria::expr()->eq( $key, $value ));
                  $qb->addCriteria( $criteria );
                  break;
              default:
                  throw new InvalidArgumentException( 'Query parameter, '.$key.', is not recognized.');
          }
      }

      // return the array of records, or an empty array if no matches found
      return $qb->getQuery()->getResult() ?? [];
  }


  
    /**
     * @inheritdoc
     */
    public function save(Student $student): void {
        try {
            $this->entityManager->persist($student);
        } catch(ORMInvalidArgumentException | ORMException $ex) {
            $this->logger->error("Error saving Student", ['exception' => $ex]);
            throw $ex;
        }

        try {
            $this->entityManager->flush();
        } catch(OptimisticLockException | ORMException $ex) {
            $this->logger->error("Error saving Student", ['exception' => $ex]);
            throw $ex;
        }
    }

}
