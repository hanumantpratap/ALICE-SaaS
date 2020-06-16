<?php
declare(strict_types=1);

namespace App\Actions\Visit;

use App\Actions\Action;
use Psr\Log\LoggerInterface;
use App\Domain\Visit\VisitorTypeRepository;

abstract class VisitorTypeAction extends Action
{
    /**
     * @param LoggerInterface $logger
     * @param VisitorTypeRepository $visitorTypeRepository
     */

    public function __construct(LoggerInterface $logger, VisitorTypeRepository $visitorTypeRepository)
    {
        $this->visitorTypeRepository = $visitorTypeRepository;
        parent::__construct($logger);
    }
}
