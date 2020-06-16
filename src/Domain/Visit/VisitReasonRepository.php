<?php
declare(strict_types=1);

namespace App\Domain\Visit;

interface VisitReasonRepository
{
    /**
     * @return VisitReason[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return VisitReason
     */
    public function findVisitReasonOfId(int $id): VisitReason;
}
