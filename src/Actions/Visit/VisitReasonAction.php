<?php
declare(strict_types=1);

namespace App\Actions\Visit;

use App\Actions\Action;
use Psr\Log\LoggerInterface;
use App\Domain\Visit\VisitReasonRepository;

abstract class VisitReasonAction extends Action
{
    /**
     * @param LoggerInterface $logger
     * @param VisitorTypeRepository $visitorTypeRepository
     */

    public function __construct(LoggerInterface $logger, VisitReasonRepository $visitReasonRepository)
    {
        $this->visitReasonRepository = $visitReasonRepository;
        parent::__construct($logger);
    }
}
