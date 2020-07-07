<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\User;

use App\Domain\User\User;
use App\Exceptions;
use App\Domain\User\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Doctrine\ORM\OptimisticLockException;
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

    public function findAll(): array {
      $query = $this->entityManager->createQueryBuilder("u")
                    ->from(User::class, "u")
                    ->select('u')
                    ->where('u.accessType = 2')
                    ->orWhere('u.accessType = 4');

      return $query->getQuery()->getResult();
    }

    // Retrieving a list of users with their notification group is very slow through doctrine, as it makes a separate query call for every single user.
    // This function grabs everything in two calls and merges them together.
    public function findAllWithNotificationGroups(): array {
      $users = $this->findAll();

      // get notification groups assignments
      $sql = "SELECT
               Groups.id As \"notificationGroupId\",
               Groups.name AS \"notificationGroupName\",
               GroupUsers.user_id AS \"userId\",
               GroupUsers.email,
               GroupUsers.text
              FROM visitor_management.notification_groups AS Groups
              LEFT JOIN visitor_management.notification_groups_has_users AS GroupUsers
                ON Groups.id = GroupUsers.notification_group_id";

      $query = $this->entityManager->getConnection()->query($sql);
      $group_users = $query->fetchAll();
      
      // index group_users by userId to speed up merge
      foreach ($group_users as $group_user) {
        $userId = $group_user['userId'];
        if (!isset($group_users_ix[$userId])) {
           $group_users_ix[$userId] = [];
        }
        
        unset($group_user['userId']);
        $group_users_ix[$userId][] = $group_user;
      }

      // Merge
      foreach ($users as &$user) {
        if (isset($group_users_ix[$user->id])) {
          $user->notificationGroupsList = $group_users_ix[$user->id];
        }
        else {
          $user->notificationGroupsList = [];
        }
      }

      return $users;
    }

    public function findUserOfId(int $id): User {
      /** @var User $User */
      $user = $this->repository->findOneBy(['id' => $id]);

      if (!is_null($user)) {
          return $user;
      }

      throw new Exceptions\NotFoundException('The User you requested does not exist.');
    }

    public function findUserOfGlobalId(int $globalUserId): User {
      /** @var User $User */
      $user = $this->repository->findOneBy(['globalUserId' => $globalUserId]);

      if (!is_null($user)) {
          return $user;
      }

      throw new Exceptions\NotFoundException('The User you requested does not exist.');
    }


    public function findUsersByEmail(string $email) {
      $query = $this->entityManager->createQueryBuilder("u")
                    ->from(User::class, "u")
                    ->select('u')
                    ->leftJoin('u.person', 'p')
                    ->leftJoin('p.email', 'e')
                    ->where('LOWER(u.login) = :email')
                    ->orWhere('LOWER(e.emailAddress) = :email')
                    ->setParameter('email', $email);

      return $query->getQuery()->getResult();
    }
    
    /**
     * @inheritdoc
     */
    public function save(User $user): void {
        try {
            $this->entityManager->persist($user);
        } catch(ORMInvalidArgumentException | ORMException $ex) {
            $this->logger->error("Error saving User", ['exception' => $ex]);
            throw $ex;
        }

        try {
            $this->entityManager->flush();
        } catch(OptimisticLockException | ORMException $ex) {
            $this->logger->error("Error saving User", ['exception' => $ex]);
            throw $ex;
        }
    }
}