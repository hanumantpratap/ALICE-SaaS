<?php
declare(strict_types=1);

namespace App\Actions\NotificationGroup;

use App\Actions\Action;
use Psr\Log\LoggerInterface;
use App\Domain\NotificationGroup\NotificationGroupRepository;
use App\Domain\User\UserRepository;
use App\Domain\Visit\VisitRepository;
use App\Classes\Mailer;

abstract class NotificationGroupAction extends Action
{
    /**
     * @param LoggerInterface $logger
     * @param NotificationGroupRepository $notificationGroupRepository
     * @param UserRepository $userRepositorym
     * @param VisitRepository $visitRepository;
     * @param Mailer $mailer
     */

    public function __construct(LoggerInterface $logger, NotificationGroupRepository $notificationGroupRepository, UserRepository $userRepository, VisitRepository $visitRepository, Mailer $mailer)
    {
        parent::__construct($logger);
        $this->notificationGroupRepository = $notificationGroupRepository;
        $this->userRepository = $userRepository;
        $this->visitRepository = $visitRepository;
        $this->mailer = $mailer;
    }
}
