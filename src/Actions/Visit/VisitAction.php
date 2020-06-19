<?php
declare(strict_types=1);

namespace App\Actions\Visit;

use App\Actions\Action;
use Psr\Log\LoggerInterface;
use App\Domain\Visit\VisitRepository;
use App\Domain\User\UserRepository;
use App\Domain\Visit\VisitorTypeRepository;
use App\Domain\Visit\VisitReasonRepository;

abstract class VisitAction extends Action
{
    /**
     * @param LoggerInterface $logger
     * @param VisitRepository $visitRepository
     * @param VisitorTypeRepository $visitorTypeRepository
     * @param visitReasonRepository $visitReasonRepository
     */

    public function __construct(LoggerInterface $logger, VisitRepository $visitRepository, UserRepository $userRepository, VisitorTypeRepository $visitorTypeRepository, visitReasonRepository $visitReasonRepository)
    {
        $this->visitRepository = $visitRepository;
        $this->userRepository = $userRepository;
        $this->visitorTypeRepository = $visitorTypeRepository;
        $this->visitReasonRepository = $visitReasonRepository;
        parent::__construct($logger);
    }
}
