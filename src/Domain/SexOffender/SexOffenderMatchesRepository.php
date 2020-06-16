<?php
declare(strict_types=1);

namespace App\Domain\SexOffender;

interface SexOffenderMatchesRepository
{
    /**
     * @return SexOffenderMatches[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return SexOffenderMatches
     */
    public function findMatchesByOffenderId(int $id): SexOffenderMatches;

    /**
     * @param int $id
     * @return SexOffenderMatches
     */
    public function findMatchesByPersonId(int $id): SexOffenderMatches;

    /**
     * @param SexOffenderMatches $group
     * @return bool
     */
    public function save(SexOffenderMatches $group): void;
}
