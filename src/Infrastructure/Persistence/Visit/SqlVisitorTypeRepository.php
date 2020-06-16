<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Visit;

use App\Exceptions;
use App\Domain\Visit\VisitorType;
use App\Domain\Visit\VisitorTypeRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Doctrine\Persistence\ObjectRepository;
use Psr\Log\LoggerInterface;

final class SqlVisitorTypeRepository implements VisitorTypeRepository
{
    private LoggerInterface $logger;
    private EntityManagerInterface $entityManager;
    private ObjectRepository $repository;

    public function __construct(LoggerInterface $logger, EntityManagerInterface $entityManager)
    {
      $this->logger = $logger;
      $this->entityManager = $entityManager;
      $this->repository = $entityManager->getRepository(VisitorType::class);
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
    public function findVisitorTypeOfId(int $id): VisitorType {
      /** @var VisitorType $visitorType */
      $visitorType = $this->repository->findOneBy(['id' => $id]);

      if (!is_null($visitorType)) return $visitorType;

      throw new Exceptions\NotFoundException('The Visit you requested does not exist.');
    }

    /**
     * @inheritdoc
     */
    public function save(VisitorType $visitorType): void {
        try {
            $this->entityManager->persist($visitorType);
        } catch(ORMInvalidArgumentException | ORMException $ex) {
            $this->logger->error("Error saving Visitor Type", ['exception' => $ex]);
            throw $ex;
        }

        try {
            $this->entityManager->flush();
        } catch(OptimisticLockException | ORMException $ex) {
            $this->logger->error("Error saving Visitor Type", ['exception' => $ex]);
            throw $ex;
        }
    }
}