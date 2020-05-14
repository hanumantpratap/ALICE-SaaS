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

/**
 * @Entity
 * @Table(name="visitor_flags", schema="visitor_management")
 */
class Flag {
  /**
   * @Id
   * @GeneratedValue
   * @Column
   */
  public ?int $id;

  /** @Column(name="people_id") */
  public int $personId;

  /** @Column(name="building_id") */
  public int $buildingId;

  /** @Column */
  public ?string $type;

  /** @Column */
  public ?string $reason;

  /** @Column(name="date_created", columnDefinition="TIMESTAMP DEFAULT CURRENT_TIMESTAMP") */
  public DateTime $createdAt;

  /** @Column(name="start_date", columnDefinition="TIMESTAMP DEFAULT NULL") */
  public ?DateTime $startDate;

  /** @Column(name="end_date", columnDefinition="TIMESTAMP DEFAULT NULL") */
  public ?DateTime $endDate;

  /**
   * @ManyToOne(targetEntity="Person", inversedBy="flag")
   * @JoinColumn(name="people_id", referencedColumnName="person_id")
   */
  protected Person $person;
}
