<?php
declare(strict_types=1);

namespace App\Domain\Person;

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
     * @param Person Person
     * @return bool
     * @throws PersonNotCreatedException
     */
    public function save(Person $person): void;
}
