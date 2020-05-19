<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\User;

use App\Domain\User\User;
use App\Exceptions;
use App\Domain\User\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Psr\Log\LoggerInterface;

final class SqlUserRepository implements UserRepository
{
    private LoggerInterface $logger;
    private EntityManagerInterface $entityManager;
    private ObjectRepository $repository;

    public function __construct(LoggerInterface $logger, EntityManagerInterface $entityManager)
    {
      $this->logger = $logger;
      $this->entityManager = $entityManager;
      $this->repository = $entityManager->getRepository(User::class);
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
    public function findUserOfId(int $id): User {
      /** @var User $User */
      $user = $this->repository->findOneBy(['id' => $id]);

      if (!is_null($user)) {
          return $user;
      }

      throw new Exceptions\NotFoundException('The User you requested does not exist.');
    }
}