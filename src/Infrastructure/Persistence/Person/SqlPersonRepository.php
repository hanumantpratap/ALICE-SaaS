<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Person;

use App\Domain\Person\Person;
use App\Domain\Person\PersonNotFoundException;
use App\Domain\Person\PersonRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Doctrine\Persistence\ObjectRepository;
use Psr\Log\LoggerInterface;

final class SqlPersonRepository implements PersonRepository
{
    private LoggerInterface $logger;
    private EntityManagerInterface $entityManager;
    private ObjectRepository $repository;

    public function __construct(LoggerInterface $logger, EntityManagerInterface $entityManager)
    {
      $this->logger = $logger;
      $this->entityManager = $entityManager;
      $this->repository = $entityManager->getRepository(Person::class);
    }

    /**
     * @inheritdoc
     */
    public function findAll(): array {
      return $this->repository->findAll();
    }

    /**
     * @inheritdoc
     */
    public function findPersonOfId(int $id): Person {
      $person = $this->repository->findOneBy(['personId' => $id]);

      if (!is_null($person)) {
        // For a single record, attach the Blacklist to the public array
        // This allows it to be easily serialized and prevents too many queries
        // on listing calls.
        $person->blacklistArray = $person->getBlacklist()->toArray();
        return $person;
      }

      throw new PersonNotFoundException();
    }

    /**
     * @inheritdoc
     */
    public function findPersonsOfName(string $name): array {

        /** @var Criteria */
        $criteria = Criteria::create()
                    ->where(Criteria::expr()->contains("displayName", $name));

        $persons = $this->entityManager->createQueryBuilder("p")
                    ->from(Person::class, "p")
                    ->select("p")
                    ->addCriteria($criteria)
                    ->getQuery()
                    ->getResult();

        if (!is_null($persons) && !empty($persons)) {
            return $persons;
        }

        throw new PersonNotFoundException();
    }

    /**
     * @inheritdoc
     */
    public function save(Person $person): void {
        try {
            $this->entityManager->persist($person);
        } catch(ORMInvalidArgumentException | ORMException $ex) {
            $this->logger->error("Error saving Person", ['exception' => $ex]);
            throw $ex;
        }

        try {
            $this->entityManager->flush();
        } catch(OptimisticLockException | ORMException $ex) {
            $this->logger->error("Error saving Person", ['exception' => $ex]);
            throw $ex;
        }
    }
}