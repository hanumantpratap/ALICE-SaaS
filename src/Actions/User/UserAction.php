<?php
declare(strict_types=1);

namespace App\Actions\User;

use App\Actions\Action;
use Psr\Log\LoggerInterface;
use App\Domain\User\UserRepository;
use App\Domain\NotificationGroup\NotificationGroupRepository;
use App\Classes\AuthService;

abstract class UserAction extends Action
{
    /**
     * @param LoggerInterface $logger
     * @param UserRepository $userRepository
     * @param NotificationGroupRepository $notificationGroupRepository
     * @param AuthService $authService
     */

    public function __construct(LoggerInterface $logger, UserRepository $userRepository, NotificationGroupRepository $notificationGroupRepository, AuthService $authService)
    {
        parent::__construct($logger);
        $this->userRepository = $userRepository;
        $this->notificationGroupRepository = $notificationGroupRepository;
        $this->authService = $authService;
    }
}
