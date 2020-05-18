<?php
declare(strict_types=1);

namespace App\Domain\Person;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\SequenceGenerator;

/**
 * @Entity
 * @Table(name="person_names", schema="public")
 */
class PersonName {
  /**
   * @Id
   * @GeneratedValue
   * @Column(name="pname_id")
   * @SequenceGenerator(sequenceName="pname_id_seq")
   */
  public ?int $id;

  /** @Column(name="person_id") */
  public int $personId;

  /** @Column(name="name_type") */
  public int $nameType = 2;

  /** @Column(name="given_name") */
  public ?string $givenName;

  /** @Column(name="middle_name") */
  public ?string $middleName;

  /** @Column(name="family_name") */
  public ?string $familyName;

  /** @Column(name="nick_name") */
  public ?string $nickName;

  /** @Column */
  public ?string $suffix;

  /** @Column */
  public ?string $title;

  /**
   * @OneToOne(targetEntity="Person", inversedBy="name")
   * @JoinColumn(name="person_id", referencedColumnName="person_id")
   */
  protected Person $person;

  public function getGivenName() {
    return $this->givenName;
  }

  public function setGivenName(string $givenName) {
    $this->givenName = $givenName;
  }

  public function getMiddleName() {
    return $this->middleName;
  }

  public function setMiddleName(string $middleName) {
    $this->middleName = $middleName;
  }

  public function getFamilyName() {
    return $this->familyName;
  }

  public function setFamilyName(string $familyName) {
    $this->familyName = $familyName;
  }

  public function setPerson(Person $person) {
    $this->person = $person;
  }
}
