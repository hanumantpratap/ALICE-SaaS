<?php
declare(strict_types=1);

namespace App\Domain\Building;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\SequenceGenerator;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="teams", schema="prepared")
 */
class Building {
  /**
   * @Id
   * @GeneratedValue
   * @Column(name="team_id")
   * @SequenceGenerator(sequenceName="prepared.team_id_seq")
   */
  public ?int $id;

  /** @Column(name="team_name") */
  public ?string $name;

  /** @Column(name="team_address") */
  public ?string $address;

  /** @Column(name="team_city") */
  public ?string $city;

  /** @Column(name="team_state") */
  public ?string $state;

  /** @Column(name="team_zip") */
  public ?string $zip;

  /** @Column(name="team_country") */
  public string $country;

  /** @Column(name="team_county") */
  public ?string $county;

  /** @Column(name="team_level") */
  private int $level;

  /** @Column(name="mt_id") */
  private int $mtid;

  /** @Column */
  public bool $active;

  /** @OneToMany(targetEntity="SubBuilding", mappedBy="parent", cascade={"persist", "remove"}, orphanRemoval=true) */
  protected Collection $subBuildings;

  public function getName() {
    return $this->name;
  }

  public function setName(string $name) {
    $this->name = $name;
  }

  public function getAddress() {
    return $this->address;
  }

  public function setAddress(string $address) {
    $this->address = $address;
  }

  public function getCity() {
    return $this->city;
  }

  public function setCity(string $city) {
    $this->city = $city;
  }

  public function getState() {
    return $this->state;
  }

  public function setState(string $state) {
    $this->state = $state;
  }

  public function getZip() {
    return $this->zip;
  }

  public function setZip(string $zip) {
    $this->zip = $zip;
  }

  public function getCounty() {
    return $this->county;
  }

  public function setCounty(string $county) {
    $this->county = $county;
  }

  public function getLevel() {
    return $this->level;
  }

  public function setLevel(int $level) {
    $this->level = $level;
  }

  public function setMtid(int $mtid) {
    $this->mtid = $mtid;
  }

  public function addSubBuilding(SubBuilding $subBuilding) {
    $subBuilding->setParent($this);
    $this->subBuildings->add($subBuilding);
  }

  public function __construct() {
    $this->level = 8;
    $this->country = "United States";
    $this->active = true;
    $this->subBuildings = new ArrayCollection();
  }
}
