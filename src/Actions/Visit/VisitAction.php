<?php
declare(strict_types=1);

namespace App\Actions\Visit;

use App\Actions\Action;
use Psr\Log\LoggerInterface;
use App\Domain\Visit\VisitRepository;
use App\Domain\User\UserRepository;
use App\Domain\Visit\VisitorTypeRepository;
use App\Domain\Visit\VisitReasonRepository;
use App\Domain\Person\PersonRepository;
use App\Domain\Student\StudentRepository;
use App\Domain\Building\BuildingRepository;


abstract class VisitAction extends Action
{
    /**
     * @param LoggerInterface $logger
     * @param VisitRepository $visitRepository
     * @param VisitorTypeRepository $visitorTypeRepository
     * @param visitReasonRepository $visitReasonRepository
     * @param PersonRepository $personRepository
     * @param BuildingRepository $buildingRepository
     */

    public function __construct(LoggerInterface $logger, VisitRepository $visitRepository, UserRepository $userRepository, VisitorTypeRepository $visitorTypeRepository,
        visitReasonRepository $visitReasonRepository, PersonRepository $personRepository, StudentRepository $studentRepository, BuildingRepository $buildingRepository)
    {
        $this->visitRepository = $visitRepository;
        $this->userRepository = $userRepository;
        $this->visitorTypeRepository = $visitorTypeRepository;
        $this->visitReasonRepository = $visitReasonRepository;
        $this->personRepository = $personRepository;
        $this->studentRepository = $studentRepository;
        $this->buildingRepository = $buildingRepository;
        parent::__construct($logger);
    }
}
