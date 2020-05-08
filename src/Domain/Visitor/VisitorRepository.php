<?php
declare(strict_types=1);

namespace App\Domain\Visitor;

interface VisitorRepository
{
    /**
     * @return Visitor[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return Visitor
     * @throws VisitorNotFoundException
     */
    public function findVisitorOfId(int $id): Visitor;

    /**
     * @param string $search
     * @return Visitor[]
     * @throws VisitorNotFoundException
     */
    public function findVisitorsOfName(string $name): array;

    /**
     * @param Visitor visitor
     * @return bool
     * @throws VisitorNotCreatedException
     */
    public function save(Visitor $visitor): void;
}
