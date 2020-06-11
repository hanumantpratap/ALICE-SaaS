<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\SexOffender;

use App\Exceptions;
use App\Domain\SexOffender\SexOffender;
use App\Domain\SexOffender\SexOffenderRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Doctrine\Persistence\ObjectRepository;
use Psr\Log\LoggerInterface;

final class SqlSexOffenderRepository implements SexOffenderRepository
{
    private LoggerInterface $logger;
    private EntityManagerInterface $entityManager;
    private ObjectRepository $repository;

    public function __construct(LoggerInterface $logger, EntityManagerInterface $entityManager)
    {
      $this->logger = $logger;
      $this->entityManager = $entityManager;
      $this->repository = $entityManager->getRepository(SexOffender::class);
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
    public function findSexOffenderOfId(int $id): SexOffender {
      /** @var SexOffender $sexOffender */
      $sexOffender = $this->repository->findOneBy(['offender_id' => $id]);

      if (!is_null($sexOffender)) {
          return $sexOffender;
      }

      throw new Exceptions\NotFoundException('The sex offender you requested does not exist.');
    }

    /**
     * @inheritdoc
     */
    public function save(SexOffender $sexOffender): void {
        try {
            $this->entityManager->persist($sexOffender);
        } catch(ORMInvalidArgumentException | ORMException $ex) {
            $this->logger->error("Error saving sex offender", ['exception' => $ex]);
            throw $ex;
        }

        try {
            $this->entityManager->flush();
        } catch(OptimisticLockException | ORMException $ex) {
            $this->logger->error("Error saving sex offender", ['exception' => $ex]);
            throw $ex;
        }
    }
}
