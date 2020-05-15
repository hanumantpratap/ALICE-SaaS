<?php
declare(strict_types=1);

namespace App\Actions\Visit;

use Psr\Http\Message\ResponseInterface as Response;
use App\Actions\Action;
use Psr\Log\LoggerInterface;
use App\Domain\Visit\VisitRepository;

abstract class VisitAction extends Action
{
    /**
     * @param LoggerInterface $logger
     * @param VisitRepository $visitRepository
     */

    public function __construct(LoggerInterface $logger, VisitRepository $visitRepository)
    {
        $this->visitRepository = $visitRepository;
        parent::__construct($logger);
    }
}
