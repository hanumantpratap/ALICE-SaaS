<?php
declare(strict_types=1);

namespace App\Actions\Building;

use App\Actions\Action;
use Psr\Log\LoggerInterface;
use App\Domain\Building\BuildingRepository;

abstract class BuildingAction extends Action
{
    /**
     * @param LoggerInterface $logger
     * @param BuildingRepository $buildingRepository
     */

    public function __construct(LoggerInterface $logger, BuildingRepository $buildingRepository)
    {
        $this->buildingRepository = $buildingRepository;
        parent::__construct($logger);
    }
}
