<?php
declare(strict_types=1);

namespace App\Domain\Visit;

use App\Domain\Person\Person;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\DBAL\Types\VarDateTimeType;

/**
 * @Entity
 * @Table(name="visits", schema="visitor_management")
 */
class Visit {
  /**
   * @Id
   * @GeneratedValue
   * @Column(name="id")
   */
  public ?int $id;

  /** @Column(name="people_id") */
  public int $personId;

  /** @Column(name="date_created") */
  public ?string $dateCreated;

  /** @Column(name="reason_id") */
  public ?int $reasonId;

  /** @Column(name="check_in") */
  public ?string $checkIn;

  /** @Column(name="check_out") */
  public ?string $checkOut;

  /** @Column(name="user_id") */
  public ?int $userId;

  /** @Column(name="identification_id") */
  public ?int $identificationId;

  /** @Column */
  public ?string $notes;

  /** @Column(name="estimated_check_in") */
  public ?string $estimatedCheckIn;

  /** @Column(name="estimated_check_out") */
  public ?string $estimatedCheckOut;

  /**
   * @ManyToOne(targetEntity="\App\Domain\Person\Person")
   * @JoinColumn(name="people_id", referencedColumnName="person_id")
   */
  public \App\Domain\Person\Person $person;

  public function getPerson() {
    return $this->person;
  }
}
