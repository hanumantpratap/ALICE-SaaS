<?php
declare(strict_types=1);

namespace App\Actions\Visit;

use Psr\Http\Message\ResponseInterface as Response;
use App\Actions\Action;
use Psr\Log\LoggerInterface;
use App\Classes\VisitsService;

abstract class VisitAction extends Action
{
    /**
     * @param LoggerInterface $logger
     * @param VisitsService $visitsService
     */

    public function __construct(LoggerInterface $logger, VisitsService $visitsService)
    {
        $this->logger = $logger;
        $this->visitsService = $visitsService;
    }
}
