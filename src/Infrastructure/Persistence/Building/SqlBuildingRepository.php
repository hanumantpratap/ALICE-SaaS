<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Building;

use App\Exceptions;
use App\Domain\Building\Building;
use App\Domain\Building\BuildingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
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
      return $this->repository->findBy(array('active' => true));
    }
}