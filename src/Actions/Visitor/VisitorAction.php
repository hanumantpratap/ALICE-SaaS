<?php
declare(strict_types=1);

namespace App\Actions\Visitor;

use App\Actions\Action;
use App\Domain\Visitor\VisitorRepository;
use Psr\Log\LoggerInterface;

abstract class VisitorAction extends Action
{
    /**
     * @var VisitorRepository
     */
    protected $visitorRepository;

    /**
     * @param LoggerInterface $logger
     * @param VisitorRepository  $visitorRepository
     */
    public function __construct(LoggerInterface $logger, VisitorRepository $visitorRepository)
    {
        parent::__construct($logger);
        $this->visitorRepository = $visitorRepository;
    }
}