<?php
declare(strict_types=1);

namespace App\Actions\Visit;

use App\Actions\Action;
use Psr\Log\LoggerInterface;
use App\Domain\Visit\VisitRepository;
use App\Domain\User\UserRepository;

abstract class VisitAction extends Action
{
    /**
     * @param LoggerInterface $logger
     * @param VisitRepository $visitRepository
     */

    public function __construct(LoggerInterface $logger, VisitRepository $visitRepository, UserRepository $userRepository)
    {
        $this->visitRepository = $visitRepository;
        $this->userRepository = $userRepository;
        parent::__construct($logger);
    }
}
