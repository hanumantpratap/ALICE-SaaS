<?php
declare(strict_types=1);

namespace Tests\Infrastructure\Persistence\Person;

use App\Domain\Person\BlacklistItem;
use App\Domain\Person\Person;
use App\Infrastructure\Persistence\Person\SqlPersonRepository;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;
use Monolog\Logger;
use Prophecy\Prophecy\ObjectProphecy;

class SqlPersonRepositoryTest extends TestCase
{
    protected ObjectProphecy $objectRepository;
    protected ObjectProphecy $entityManager;
    protected LoggerInterface $logger;

    protected function setUp(): void {
      $this->objectRepository = $this->prophesize(ObjectRepository::class);
      $this->entityManager = $this->prophesize(EntityManagerInterface::class);
      $this->entityManager->getRepository(Argument::any())->willReturn($this->objectRepository);
      $this->logger = new Logger("");
    }

    public function testFindAll_findsAll(): void {
      $personA = new Person();
      $personA->displayName = "John Doe";
      $personB = new Person();
      $personB->displayName = "Jane Doe";
      $persons = array($personA, $personB);

      $this->objectRepository->findAll()->willReturn($persons);
      $personRepository = new SqlPersonRepository($this->logger, $this->entityManager->reveal());

      $this->assertEquals($persons, $personRepository->findAll());
    }

    public function testPersonOfId_findsOne(): void {
      $person = new Person();
      $person->personId = 1;
      $person->displayName = "John Doe";

      $blItem = new BlacklistItem();
      $blItem->personId = 1;
      $blItem->buildingId = 1;

      $person->addBlacklistItem($blItem);

      $this->objectRepository->findOneBy(Argument::any())->willReturn($person);

      $personRepository = new SqlPersonRepository($this->logger, $this->entityManager->reveal());

      $this->assertEquals($person, $personRepository->findPersonOfId(1));
    }

    public function testFindPersonOfId_findsNone(): void {
      $this->objectRepository->findOneBy(Argument::any())->willReturn(NULL);
      $personRepository = new SqlPersonRepository($this->logger, $this->entityManager->reveal());

      $this->expectException('App\Domain\Person\PersonNotFoundException');

      $persons = $personRepository->findPersonOfId(1);
    }

    public function testFindPersonsOfName_findsOne(): void {
      $qb = $this->prophesize(QueryBuilder::class);
      $query = $this->prophesize(AbstractQuery::class);

      $qb->from(Argument::any(), Argument::any())->willReturn($qb);
      $qb->select(Argument::any())->willReturn($qb);
      $qb->addCriteria(Argument::any())->willReturn($qb);
      $qb->getQuery()->willReturn($query);

      $person = new Person();
      $person->personId = 1;
      $person->displayName = "Test";
      $personArr = [$person];

      $query->getResult()->willReturn($personArr);

      $this->entityManager->createQueryBuilder(Argument::any())->willReturn($qb);

      $personRepository = new SqlPersonRepository($this->logger, $this->entityManager->reveal());

      $this->assertEquals($personArr, $personRepository->findPersonsOfName("Test"));
    }
}