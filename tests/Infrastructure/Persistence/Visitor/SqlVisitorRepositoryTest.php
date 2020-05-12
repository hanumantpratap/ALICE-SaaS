<?php
declare(strict_types=1);

namespace Tests\Infrastructure\Persistence\Visitor;

use App\Domain\Visitor\Visitor;
use App\Infrastructure\Persistence\Visitor\SqlVisitorRepository;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;
use Monolog\Logger;
use Prophecy\Prophecy\ObjectProphecy;

class SqlVisitorRepositoryTest extends TestCase
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

    public function testFindAll_findsAll() {
      $visitorA = new Visitor(1, "jdoe", "John", "Doe");
      $visitorB = new Visitor(2, "jdoe2", "Jane", "Doe");
      $visitors = array($visitorA, $visitorB);

      $this->objectRepository->findAll()->willReturn($visitors);
      $visitorRepository = new SqlVisitorRepository($this->logger, $this->entityManager->reveal());

      $this->assertEquals($visitors, $visitorRepository->findAll());
    }

    public function testVisitorOfId_findsOne() {
      $visitor = new Visitor(1, "jdoe", "John", "Doe");
      $this->objectRepository->findOneBy(Argument::any())->willReturn($visitor);
      $visitorRepository = new SqlVisitorRepository($this->logger, $this->entityManager->reveal());

      $this->assertEquals($visitor, $visitorRepository->findVisitorOfId(1));
    }

    public function testFindVisitorOfId_findsNone() {
      $this->objectRepository->findOneBy(Argument::any())->willReturn(NULL);
      $visitorRepository = new SqlVisitorRepository($this->logger, $this->entityManager->reveal());

      $this->expectException('App\Domain\Visitor\VisitorNotFoundException');

      $visitors = $visitorRepository->findVisitorOfId(1);
    }
}