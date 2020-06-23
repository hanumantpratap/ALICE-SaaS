<?php
declare(strict_types=1);

namespace App\Actions\SexOffender;

use App\Actions\Action;
use App\Classes\FamilyWatchDog;
use App\Domain\Person\PersonRepository;
use App\Domain\SexOffender\SexOffenderRepository;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;

abstract class SexOffenderAction extends Action
{
    protected FamilyWatchDog $familyWatchDog;
    protected PersonRepository $personRepository;
    protected SexOffenderRepository $sexOffenderRepository;
    protected EntityManagerInterface $entityManager;

    public function __construct(LoggerInterface $logger, FamilyWatchDog $familyWatchDog, PersonRepository $personRepository, SexOffenderRepository $sexOffenderRepository, EntityManagerInterface $entityManager)
    {
        parent::__construct($logger);
        $this->familyWatchDog = $familyWatchDog;
        $this->personRepository = $personRepository;
        $this->sexOffenderRepository = $sexOffenderRepository;
        $this->entityManager = $entityManager;
    }
}
