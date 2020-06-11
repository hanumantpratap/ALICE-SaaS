<?php
declare(strict_types=1);

namespace App\Domain\Building;

interface BuildingRepository
{
    /**
     * @return Building[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return Building
     */
    public function findBuildingOfId(int $id): Building;

    /**
     * @return Building[]
     */
    public function findActiveBuildings(): array;
    
}
