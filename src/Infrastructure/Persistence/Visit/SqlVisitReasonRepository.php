<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Visit;

use App\Exceptions;
use App\Domain\Visit\VisitReason;
use App\Domain\Visit\VisitReasonRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Doctrine\Persistence\ObjectRepository;
use Psr\Log\LoggerInterface;

final class SqlVisitReasonRepository implements VisitReasonRepository
{
    private LoggerInterface $logger;
    private EntityManagerInterface $entityManager;
    private ObjectRepository $repository;

    public function __construct(LoggerInterface $logger, EntityManagerInterface $entityManager)
    {
      $this->logger = $logger;
      $this->entityManager = $entityManager;
      $this->repository = $entityManager->getRepository(VisitReason::class);
    }

    /**
     * @inheritdoc
     */
    public function findAll(): array {
        return $this->repository->findBy([], ['id' => 'ASC']);
    }

    /**
     * @inheritdoc
     */
    public function findVisitReasonOfId(int $id): VisitReason {
      /** @var VisitReason $VisitReason */
      $VisitReason = $this->repository->findOneBy(['id' => $id]);

      if (!is_null($VisitReason)) return $VisitReason;

      throw new Exceptions\NotFoundException('The Visit Reason you requested does not exist.');
    }

    /**
     * @inheritdoc
     */
    public function save(VisitReason $VisitReason): void {
        try {
            $this->entityManager->persist($VisitReason);
        } catch(ORMInvalidArgumentException | ORMException $ex) {
            $this->logger->error("Error saving Visit Reason", ['exception' => $ex]);
            throw $ex;
        }

        try {
            $this->entityManager->flush();
        } catch(OptimisticLockException | ORMException $ex) {
            $this->logger->error("Error saving Visit Reason", ['exception' => $ex]);
            throw $ex;
        }
    }
}