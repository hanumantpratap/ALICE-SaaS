<?php
declare(strict_types=1);

namespace App\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;
use App\Actions\Action;
use Psr\Log\LoggerInterface;
use App\Domain\User\UserRepository;
use App\Domain\NotificationGroup\NotificationGroupRepository;

abstract class UserAction extends Action
{
    /**
     * @param LoggerInterface $logger
     * @param UserRepository $userRepository
     * @param NotificationGroupRepository $notificationGroupRepository
     */

    public function __construct(LoggerInterface $logger, UserRepository $userRepository, NotificationGroupRepository $notificationGroupRepository)
    {
        parent::__construct($logger);
        $this->userRepository = $userRepository;
        $this->notificationGroupRepository = $notificationGroupRepository;
    }
}
