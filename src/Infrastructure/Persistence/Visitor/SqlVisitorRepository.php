<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Visitor;

use App\Domain\Visitor\Visitor;
use App\Domain\Visitor\VisitorNotFoundException;
use App\Domain\Visitor\VisitorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Doctrine\Persistence\ObjectRepository;
use Psr\Log\LoggerInterface;

final class SqlVisitorRepository implements VisitorRepository
{
    private LoggerInterface $logger;
    private EntityManagerInterface $entityManager;
    private ObjectRepository $repository;

    public function __construct(LoggerInterface $logger, EntityManagerInterface $entityManager)
    {
      $this->logger = $logger;
      $this->entityManager = $entityManager;
      $this->repository = $entityManager->getRepository(Visitor::class);
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
    public function findVisitorOfId(int $id): Visitor {
        $visitor = $this->repository->findOneBy(['id' => $id]);
        if (!is_null($visitor)) {
            return $visitor;
        }

        throw new VisitorNotFoundException();
    }

    /**
     * @inheritdoc
     */
    public function findVisitorsOfName(string $name): array {
        $visitors = $this->repository->findBy(['name' => $name]);
        if (!is_null($visitors) && !empty($visitors)) {
            return $visitors;
        }

        throw new VisitorNotFoundException();
    }

    /**
     * @inheritdoc
     */
    public function save(Visitor $visitor): void {
        try {
            $this->entityManager->persist($visitor);
        } catch(ORMInvalidArgumentException | ORMException $ex) {
            $this->logger->error("Error saving Visitor", ['exception' => $ex]);
            throw $ex;
        }

        try {
            $this->entityManager->flush();
        } catch(OptimisticLockException | ORMException $ex) {
            $this->logger->error("Error saving Visitor", ['exception' => $ex]);
            throw $ex;
        }
    }
}