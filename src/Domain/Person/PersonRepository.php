<?php
declare(strict_types=1);

namespace App\Domain\Person;

use Doctrine\Common\Collections\Collection;

interface PersonRepository
{
    /**
     * @return Person[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return Person
     * @throws PersonNotFoundException
     */
    public function findPersonOfId(int $id): Person;

    /**
     * @param string $search
     * @return Person[]
     * @throws PersonNotFoundException
     */
    public function findPersonsOfName(string $name): array;

    /**
     * @param string $params
     * @return Person[]
     * @throws InvalidArgumentException
     */
    public function findPersonsByParams(array $params): array;

    /**
     * @param int $threshold
     * @param int $limit
     * @return Collection
     * @throws PersonNotFoundException
     */
    public function getFrequentVisitors(int $threshold, int $limit): array;

    /**
     * @param Person Person
     * @return bool
     * @throws PersonNotCreatedException
     */
    public function save(Person $person): void;
}
