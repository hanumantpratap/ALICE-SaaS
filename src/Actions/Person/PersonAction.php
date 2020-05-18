<?php
declare(strict_types=1);

namespace App\Actions\Person;

use App\Actions\Action;
use App\Domain\Person\PersonRepository;
use Psr\Log\LoggerInterface;

abstract class PersonAction extends Action
{
    protected PersonRepository $personRepository;

    public function __construct(LoggerInterface $logger, PersonRepository $personRepository)
    {
        parent::__construct($logger);
        $this->personRepository = $personRepository;
    }
}
