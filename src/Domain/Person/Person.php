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
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\SequenceGenerator;

/**
 * @Entity
 * @Table(name="people", schema="public")
 */
class Person {
  /**
   * @Id
   * @GeneratedValue
   * @Column(name="person_id")
   * @SequenceGenerator(sequenceName="person_id_seq")
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

  /** @OneToOne(targetEntity="PersonName", mappedBy="person", cascade={"persist", "remove"}) */
  public ?PersonName $name;

  /** @OneToOne(targetEntity="PersonDemographics", mappedBy="person", cascade={"persist", "remove"}) */
  public ?PersonDemographics $demographics;

  /** @OneToMany(targetEntity="PersonPhone", mappedBy="person") */
  protected Collection $phones;

  /** @OneToOne(targetEntity="PersonEmail", mappedBy="person", cascade={"persist", "remove"}) */
  public ?PersonEmail $email = null;
  
  /** @OneToMany(targetEntity="Flag", mappedBy="person") */
  protected Collection $flags;

  /** @OneToMany(targetEntity="BlacklistItem", mappedBy="person", cascade={"persist", "remove"}) */
  protected Collection $blacklist;

  public array $blacklistArray;
  
  /** @OneToMany(targetEntity="Identification", mappedBy="person", cascade={"persist", "remove"}) */
  protected Collection $identifications;

  public function getPersonId() {
    return $this->personId;
  }

  public function getStatus() {
    return $this->status;
  }

  public function setStatus(int $status) {
    $this->status = $status;
  }

  public function getDisplayName() {
    return $this->displayName;
  }

  public function getName() {
    return $this->name;
  }

  public function setName(PersonName $name) {
    $this->name = $name;
    $this->displayName = $name->getFamilyName() . ', ' . $name->getGivenName();
  }
  
  public function getEmail() {
    return $this->email;
  }

  public function setEmail(PersonEmail $email) {
    $this->email = $email;
  }
  
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
    return $this->blacklist->exists(fn($key, $value) => $value->buildingId == $buildingId);
  }

  public function getIdentifications() {
    return $this->identifications;
  }

  public function addIdentification(Identification $identification) {
    $identification->setPerson($this);
    $this->identifications->add($identification);
  }

  public function removeIdentification(Identification $identification): void {
    $this->identifications->removeElement($identification);
  }

  public function getDemographics() {
    return $this->demographics;
  }

  public function __construct() {
    $this->name = new PersonName();
    $this->demographics = new PersonDemographics();
    $this->demographics->setPerson($this);
    $this->phones = new ArrayCollection();
    $this->flags = new ArrayCollection();
    $this->blacklist = new ArrayCollection();
    $this->identifications = new ArrayCollection();
  }
}