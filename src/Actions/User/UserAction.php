<?php
declare(strict_types=1);

namespace App\Actions\User;

use App\Actions\Action;
use Psr\Log\LoggerInterface;
use App\Domain\User\UserRepository;
use App\Domain\NotificationGroup\NotificationGroupRepository;
use App\Classes\AuthService;
use App\Classes\TokenProcessor;
use App\Classes\Mailer;
use Psr\Container\ContainerInterface;

abstract class UserAction extends Action
{
    /**
     * @param LoggerInterface $logger
     * @param UserRepository $userRepository
     * @param NotificationGroupRepository $notificationGroupRepository
     * @param AuthService $authService
     * @param TokenProcessor $tokenProcessor
     * @param Mailer $mailer
     * @param ContainerInterface $container
     */

    public function __construct(LoggerInterface $logger, UserRepository $userRepository, NotificationGroupRepository $notificationGroupRepository, AuthService $authService, TokenProcessor $tokenProcessor, Mailer $mailer, ContainerInterface $container)
    {
        parent::__construct($logger);
        $this->userRepository = $userRepository;
        $this->notificationGroupRepository = $notificationGroupRepository;
        $this->authService = $authService;
        $this->tokenProcessor = $tokenProcessor;
        $this->mailer = $mailer;
        $this->container = $container;
    }
}
