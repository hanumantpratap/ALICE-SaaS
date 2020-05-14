<?php
declare(strict_types=1);

namespace App\Actions\Visit;

use Psr\Http\Message\ResponseInterface as Response;
use App\Actions\Action;
use Psr\Log\LoggerInterface;
use App\Services\VisitsService;
use Doctrine\ORM\EntityManagerInterface;
use App\Domain\Visit\Visit;

abstract class VisitAction extends Action
{
    /**
     * @param LoggerInterface $logger
     * @param VisitsService $visitsService
     * @param EntityManagerInterface $entityManager
     */

    public function __construct(LoggerInterface $logger, VisitsService $visitsService, EntityManagerInterface $entityManager)
    {
        $this->visitsService = $visitsService;
        $this->repository = $entityManager->getRepository(Visit::class);
        parent::__construct($logger);
    }
}
