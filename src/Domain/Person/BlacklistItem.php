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
 * @Table(name="visitor_blacklist", schema="visitor_management")
 */
class BlacklistItem {
  /**
   * @Id
   * @GeneratedValue
   * @Column
   */
  public ?int $id;

  /** @Column(name="person_id") */
  public int $personId;

  /** @Column(name="building_id") */
  public int $buildingId;

  /** @Column */
  public ?string $reason;

  /** @Column */
  public ?string $notes;

  /** @Column(name="user_id") */
  public int $userId;

  /** @Column(name="date_created", nullable=true, type="datetime") */
  public DateTime $createdAt;

  /** @Column(name="date_updated", nullable=true, type="datetime") */
  public ?DateTime $updatedAt;

  /**
   * @ManyToOne(targetEntity="Person", inversedBy="blacklist")
   * @JoinColumn(name="person_id", referencedColumnName="person_id")
   */
  protected Person $person;
}
