<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Person;

use App\Domain\Person\Person;
use App\Domain\Person\PersonDemographics;
use App\Domain\Person\PersonNotFoundException;
use App\Domain\Person\PersonRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Doctrine\Persistence\ObjectRepository;
use InvalidArgumentException;
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

        return [];
        //throw new PersonNotFoundException();
    }



    // find persons based on field searches in the people and person_demographics table.
    // All search criteria in array, $params, will be concatenated by ANDs.
    public function findPersonsByParams(array $params): array {

        // declare the QueryBuilder
        $qb = $this->entityManager->createQueryBuilder("p")
            ->from(Person::class, "p")
            ->select("p")->LeftJoin( 'p.demographics', 'pd');

        // iterate through query params, and build the query
        foreach( $params as $key => $value ) {
            switch( $key ) {
                // a partial string match in a Person field
                case "displayName": case "externalId": case "type":
                    $criteria = Criteria::create()->where(Criteria::expr()->contains( $key, $value ));
                    $qb->addCriteria( $criteria );
                    break;
                 // an exact value in a Person field
                 case "status":
                    $criteria = Criteria::create()->where(Criteria::expr()->eq( $key, $value ));
                    $qb->addCriteria( $criteria );
                    break;
                // a partial string match in a demographics field
                case "marks":
                    $criteria = Criteria::create()->where(Criteria::expr()->contains( 'pd.'.$key, $value ));
                    $qb->addCriteria( $criteria );
                    break;
                // an exact value in a demographics field
                case "birthDate": case "gender": case "ethnicity": case "bloodType": case "maritalStatus":
                case "eyeColor": case "hairColor": case "height": case "weight":
                    $criteria = Criteria::create()->where(Criteria::expr()->eq( 'pd.'.$key, $value ));
                    $qb->addCriteria( $criteria );
                    break;
                default:
                    throw new InvalidArgumentException( 'Query parameter, '.$key.', is not recognized.');
            }
        }

        // return the array of persons, or an empty array if no matches found
        return $qb->getQuery()->getResult() ?? [];
    }

    /**
     * @inheritdoc
     */
    public function findPersonByIdentification(string $identificationId): Person {
        $this->logger->info("identification id: ${identificationId}");

        $persons = $this->entityManager->createQueryBuilder("p")
                    ->select('p')
                    ->from(Person::class, "p")
                    ->join('p.identifications', 'i')
                    ->where('i.id = :identificationId')
                    ->setParameter('identificationId', $identificationId)
                    ->getQuery()
                    ->getResult();

        if (!is_null($persons) && !empty($persons)) {
            return $persons[0];
        }

        throw new PersonNotFoundException();
    }

    /**
     * @inheritdoc
     */
    public function getFrequentVisitors(int $threshold, int $limit, int $buildingId): array {
        $this->logger->info("Getting up to ${limit} frequent visitors with at least ${threshold} visits.");

        $query = $this->entityManager->createQueryBuilder()
                    ->select("p, count(p.personId) as visitsCount")
                    ->from(Person::class, "p")
                    ->join("p.visits", "v")
                    ->groupBy("p.personId")
                    ->having("count(p.personId) >= :threshold")
                    ->where("v.buildingId = :buildingId")
                    ->orderBy("visitsCount", "desc")
                    ->setMaxResults($limit)
                    ->setParameter("threshold", $threshold)
                    ->setParameter("buildingId", $buildingId)
                    ->getQuery();

        $persons = $query->getResult();

        if (!is_null($persons) && !empty($persons)) {
            return $persons;
        }

        throw new PersonNotFoundException();
    }

    /**
     * @inheritdoc
     */
    public function getCurrentVisitors(int $buildingId): array {
        $persons = $this->entityManager->createQueryBuilder()
                        ->select("DISTINCT p")
                        ->from(Person::class, "p")
                        ->join("p.visits", "v")
                        ->where("v.checkOut IS NULL")
                        ->andWhere("v.buildingId = :buildingId")
                        ->setParameter("buildingId", $buildingId)
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
