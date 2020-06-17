<?php
declare(strict_types=1);

namespace App\Domain\Person;

use DateTime;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\SequenceGenerator;

/**
 * @Entity
 * @Table(name="person_phones", schema="public")
 */
class PersonPhone {
  /**
   * @Id
   * @GeneratedValue
   * @Column(name="phone_id")
   * @SequenceGenerator(sequenceName="pphone_id_seq")
   */
  public ?int $id;

  /** @Column(name="person_id") */
  public int $personId;

  /** @Column(name="phone_type") */
  public ?int $phoneType;

  /** @Column(name="phone_priority") */
  public int $phonePriority;

  /** @Column(name="phone_status") */
  public int $phoneStatus;

  /** @Column(name="phone_number") */
  public string $phoneNumber;

  /** @Column */
  public int $source;

  /** @Column(name="updated", type="datetime") */
  public ?DateTime $updated;

  /** @Column(name="phone_number_extension") */
  public ?string $extension;

  /**
   * @ManyToOne(targetEntity="Person", inversedBy="phones")
   * @JoinColumn(name="person_id", referencedColumnName="person_id")
   */
  protected Person $person;

  public function getPhoneNumber() {
    return $this->phoneNumber;
  }

  public function setPhoneNumber(string $phoneNumber) {
    $this->phoneNumber = $phoneNumber;
  }
  
  public function getType() {
    return $this->phoneType;
  }

  public function setType(int $type) {
    $this->phoneType = $type;
  }

  public function addPerson(Person $person) {
    $this->person = $person;
  }

  public function __construct() {
    $this->phonePriority = 1;
    $this->phoneStatus = 1;
    $this->updated = new DateTime();;
  }
}
