<?php
declare(strict_types=1);

namespace App\Domain\Visit;

interface VisitorTypeRepository
{
    /**
     * @return VisitorType[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return VisitorType
     */
    public function findVisitorTypeOfId(int $id): VisitorType;
}
