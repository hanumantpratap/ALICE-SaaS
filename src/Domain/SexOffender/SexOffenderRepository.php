<?php
declare(strict_types=1);

namespace App\Domain\SexOffender;

interface SexOffenderRepository
{
    /**
     * @return SexOffender[]
     */
    public function findAll(): array;

    /**
     * @param string $id
     * @return SexOffender
     */
    public function findSexOffenderOfId(string $id): SexOffender;

    /**
     * @param SexOffender $group
     * @return bool
     */
    public function save(SexOffender $group): void;
}
