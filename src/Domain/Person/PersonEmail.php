<?php
declare(strict_types=1);

namespace App\Domain\Person;

use DateTime;
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
 * @Table(name="person_email", schema="public")
 */
class PersonEmail {
  /**
   * @Id
   * @GeneratedValue
   * @Column(name="email_id")
   * @SequenceGenerator(sequenceName="pemail_id_seq")
   */
  public ?int $id;

  /** @Column(name="person_id") */
  public int $personId;

  /** @Column(name="email_type") */
  public int $emailType;

  /** @Column(name="email_priority") */
  public int $emailPriority;

  /** @Column(name="email_status") */
  public int $emailStatus;

  /** @Column(name="email_address") */
  public string $emailAddress;

  /** @Column */
  public ?int $source;

  /** @Column(name="updated", nullable=true, type="datetime") */
  public ?DateTime $updated;

  /**
   * @OneToOne(targetEntity="Person", inversedBy="email")
   * @JoinColumn(name="person_id", referencedColumnName="person_id")
   */
  protected Person $person;

  public function getEmailAddress() {
    return $this->emailAddress;
  }

  public function setEmailAddress(string $emailAddress) {
    $this->emailAddress = $emailAddress;
  }

  public function getPerson() {
    return $this->person;
  }

  public function setPerson(Person $person) {
    $this->person = $person;
  }

  public function setSource(int $source) {
    $this->source = $source;
  }

  public function __construct() {
    $this->emailType = 1;
    $this->emailPriority = 1;
    $this->emailStatus = 1;
    $this->updated = new DateTime();
  }
}
