<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Visit;

use App\Exceptions;
use App\Domain\Visit\Visit;
use App\Domain\Visit\VisitRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Doctrine\Persistence\ObjectRepository;
use Psr\Log\LoggerInterface;

final class SqlVisitRepository implements VisitRepository
{
    private LoggerInterface $logger;
    private EntityManagerInterface $entityManager;
    private ObjectRepository $repository;

    public function __construct(LoggerInterface $logger, EntityManagerInterface $entityManager)
    {
      $this->logger = $logger;
      $this->entityManager = $entityManager;
      $this->repository = $entityManager->getRepository(Visit::class);
    }

    /**
     * @inheritdoc
     */
    public function findAll(): array {
        $visits = $this->repository->findAll();

        foreach ($visits as &$visit) {
            $visit->visitor = $visit->getVisitor();
        }

        return $visits;
    }

    /**
     * @inheritdoc
     */
    public function findVisitOfId(int $id): Visit {
      /** @var Visit $visit */
      $visit = $this->repository->findOneBy(['id' => $id]);

      if (!is_null($visit)) {
          $visit->visitor = $visit->getVisitor();
          return $visit;
      }

      throw new Exceptions\NotFoundException('The Visit you requested does not exist.');
    }

    /**
     * @inheritdoc
     */
    public function save(Visit $visit): void {
        try {
            $this->entityManager->persist($visit);
        } catch(ORMInvalidArgumentException | ORMException $ex) {
            $this->logger->error("Error saving Visit", ['exception' => $ex]);
            throw $ex;
        }

        try {
            $this->entityManager->flush();
        } catch(OptimisticLockException | ORMException $ex) {
            $this->logger->error("Error saving Visit", ['exception' => $ex]);
            throw $ex;
        }
    }
}