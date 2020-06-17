<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Building;

use App\Exceptions;
use App\Domain\Building\Building;
use App\Domain\Building\BuildingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Psr\Log\LoggerInterface;

final class SqlBuildingRepository implements BuildingRepository
{
    private LoggerInterface $logger;
    private EntityManagerInterface $entityManager;
    private ObjectRepository $repository;

    public function __construct(LoggerInterface $logger, EntityManagerInterface $entityManager)
    {
      $this->logger = $logger;
      $this->entityManager = $entityManager;
      $this->repository = $entityManager->getRepository(Building::class);
    }

    function findAll(): array {
        return $this->repository->findAll();
    }

    public function findBuildingOfId(int $id): Building {
      /** @var Building $building */
      $building = $this->repository->findOneBy(['id' => $id]);

      if (!is_null($building)) {
          return $building;
      }

      throw new Exceptions\NotFoundException('The Building you requested does not exist.');
    }

    public function findActiveBuildings(): array {
      $query = $this->entityManager->createQueryBuilder("b")
                    ->from(Building::class, "b")
                    ->select('b')
                    ->where("b.active = :active")
                    ->andWhere("b.id <> b.mtid")
                    ->setParameter("active", true);

      return $query->getQuery()->getResult() ?? [];
    }

    public function save(Building $building): void {
        try {
            $this->entityManager->persist($building);
        } catch(ORMInvalidArgumentException | ORMException $ex) {
            $this->logger->error("Error saving Building", ['exception' => $ex]);
            throw $ex;
        }

        try {
            $this->entityManager->flush();
        } catch(OptimisticLockException | ORMException $ex) {
            $this->logger->error("Error saving Building", ['exception' => $ex]);
            throw $ex;
        }
    }
}