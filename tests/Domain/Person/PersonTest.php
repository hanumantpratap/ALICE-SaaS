<?php
declare(strict_types=1);

namespace Tests\Domain\Person;

use App\Domain\Person\BlacklistItem;
use App\Domain\Person\Person;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Monolog\Logger;

class PersonTest extends TestCase
{
    protected LoggerInterface $logger;

    protected function setUp(): void {
      $this->logger = new Logger("");
    }

    public function testBuildingBlacklistCheck_succeeds() {
      $buildingId = 100;

      $person = new Person();
      $person->personId = 1;

      $blItem = new BlacklistItem();
      $blItem->personId = 1;
      $blItem->buildingId = $buildingId;

      $person->addBlacklistItem($blItem);

      $this->assertTrue($person->isOnBuildingBlacklist($buildingId));
    }

    public function testBuildingBlacklistCheck_fails() {
      $buildingId = 100;

      $person = new Person();
      $person->personId = 1;

      $blItem = new BlacklistItem();
      $blItem->personId = 1;
      $blItem->buildingId = $buildingId;

      $person->addBlacklistItem($blItem);

      $this->assertFalse($person->isOnBuildingBlacklist(101));
    }

    public function testBuildBlacklistCheck_handlesEmpty() {
      $person = new Person();
      $person->personId = 1;

      $this->assertFalse($person->isOnBuildingBlacklist(101));
    }
}