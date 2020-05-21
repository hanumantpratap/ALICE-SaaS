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

    public function findAll(): array {
      return $this->repository->findAll();
    }

    // Retrieving a list of users with their notification group is very slow through doctrine, as it makes a separate query call for every single user.
    // This function grabs everything in two calls and merges them together.
    public function findAllWithNotificationGroups(): array {
      $users = $this->repository->findAll();

      // get notification groups assignments
      $sql = "SELECT
               Groups.id As \"notificationGroupId\",
               GroupUsers.user_id AS \"userId\",
               GroupUsers.building_id AS \"buildingId\"
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
}