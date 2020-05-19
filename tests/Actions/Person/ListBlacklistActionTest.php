<?php

declare(strict_types=1);

namespace Tests\Actions\Person;

use App\Actions\ActionPayload;
use App\Domain\Person\BlacklistItem;
use App\Domain\Person\Person;
use App\Domain\Person\PersonRepository;
use Psr\Log\LoggerInterface;
use Monolog\Logger;
use DI\Container;
use Tests\ActionTestCase;
use Prophecy\Prophecy\ObjectProphecy;

class ListBlacklistActionTest extends ActionTestCase
{
  protected LoggerInterface $logger;
  protected ObjectProphecy $repository;

  protected function setUp(): void
  {
    $this->logger = new Logger("");
    $this->repository = $this->prophesize(PersonRepository::class);
  }

  public function testListBlacklist_returnsArray(): void
  {
    $app = $this->getAppInstance();
    /** @var Container */
    $container = $app->getContainer();

    $person = new Person();
    $person->personId = 1;
    $person->displayName = "John Doe";

    $blItem = new BlacklistItem();
    $blItem->setPerson($person);
    $blItem->id = 1;
    $blItem->buildingId = 1;

    $person->addBlacklistItem($blItem);

    $this->repository->findPersonOfId($person->personId)->willReturn($person);
    $container->set(PersonRepository::class, $this->repository->reveal());

    $req = $this->createRequest('GET', '/persons/1/blacklist');
    $resp = $app->handle($req);
    $body = (string) $resp->getBody();

    $expected = json_encode(new ActionPayload(200, [$blItem]), JSON_PRETTY_PRINT);

    $this->assertEquals($expected, $body);
  }
}
