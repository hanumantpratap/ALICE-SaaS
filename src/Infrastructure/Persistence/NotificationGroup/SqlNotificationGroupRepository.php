<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\NotificationGroup;

use App\Exceptions;
use App\Domain\NotificationGroup\NotificationGroup;
use App\Domain\NotificationGroup\NotificationGroupRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Doctrine\Persistence\ObjectRepository;
use Psr\Log\LoggerInterface;

final class SqlNotificationGroupRepository implements NotificationGroupRepository
{
    private LoggerInterface $logger;
    private EntityManagerInterface $entityManager;
    private ObjectRepository $repository;

    public function __construct(LoggerInterface $logger, EntityManagerInterface $entityManager)
    {
      $this->logger = $logger;
      $this->entityManager = $entityManager;
      $this->repository = $entityManager->getRepository(NotificationGroup::class);
    }

    public function findAll(): array {
      return $this->repository->findAll();
    }

    public function findNotificationGroupOfId(int $id): NotificationGroup {
      /** @var NotificationGroup $group */
      $group = $this->repository->findOneBy(['id' => $id]);

      if (!is_null($group)) {
          return $group;
      }

      throw new Exceptions\NotFoundException('The Notification Group you requested does not exist.');
    }
    
    public function save(NotificationGroup $group): void {
        try {
            $this->entityManager->persist($group);
        } catch(ORMInvalidArgumentException | ORMException $ex) {
            $this->logger->error("Error saving Notification Group", ['exception' => $ex]);
            throw $ex;
        }

        try {
            $this->entityManager->flush();
        } catch(OptimisticLockException | ORMException $ex) {
            $this->logger->error("Error saving Notification Group", ['exception' => $ex]);
            throw $ex;
        }
    }
}