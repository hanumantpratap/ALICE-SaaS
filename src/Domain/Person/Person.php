<?php
declare(strict_types=1);

namespace App\Domain\Person;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use JsonSerializable;
use ReflectionObject;
use ReflectionProperty;

/**
 * @Entity
 * @Table(name="people", schema="public")
 */
class Person implements JsonSerializable {
  /**
   * @Id
   * @GeneratedValue
   * @Column(name="person_id")
   */
  public ?int $personId = null;

  /** @Column(name="status") */
  public int $status;

  /** @Column(name="display_name") */
  public ?string $displayName;

  /** @Column(name="external_id") */
  public ?string $externalId;

  /** @Column(name="type") */
  public ?string $type;

  /** @OneToOne(targetEntity="PersonName", mappedBy="person") */
  public ?PersonName $name;

  /** @OneToMany(targetEntity="PersonPhone", mappedBy="person") */
  protected Collection $phones;

  /** @OneToMany(targetEntity="Flag", mappedBy="person") */
  protected Collection $flags;

  /** @OneToMany(targetEntity="BlacklistItem", mappedBy="person") */
  protected Collection $blacklist;

  public function getBlacklist(): Collection {
    return $this->blacklist;
  }

  protected function setBlacklist(Collection $blacklist): void {
    $this->blacklist = $blacklist;
  }

  public function addBlacklistItem(BlacklistItem $item): void {
    $this->blacklist->add($item);
  }

  public function removeBlacklistItem(BlacklistItem $item): void {
    $this->blacklist->removeElement($item);
  }

  public function isOnBuildingBlacklist(int $buildingId): bool {
    return $this->blacklist->exists(fn($item) => $item->buildingId == $buildingId);
  }

  public function __construct() {
    $this->name = new PersonName();
    $this->phones = new ArrayCollection();
    $this->flags = new ArrayCollection();
    $this->blacklist = new ArrayCollection();
  }

  public function jsonSerialize() {
    return [
      "personId" => $this->personId,
      "firstName" => $this->name->givenName,
      "lastName" => $this->name->familyName,
      "blacklist" => $this->blacklist->toArray()
    ];
  }
}
