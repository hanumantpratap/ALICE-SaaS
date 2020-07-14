<?php
declare(strict_types=1);

namespace App\Domain\Visit;

interface VisitRepository
{
    /**
     * @return Visit[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return Visit
     */
    public function findVisitOfId(int $id): Visit;

    /**
     * @param mixed $filter_data     
     * @return int
     */
    public function findAllowedVisitOfBuildingId($filter_data): int;
}
