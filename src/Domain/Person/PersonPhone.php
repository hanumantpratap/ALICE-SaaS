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
 * @Table(name="person_phones", schema="public")
 */
class PersonPhone {
  /**
   * @Id
   * @GeneratedValue
   * @Column(name="phone_id")
   */
  public ?int $id = null;

  /** @Column(name="person_id") */
  public int $personId;

  /** @Column(name="phone_type") */
  public int $phoneType;

  /** @Column(name="phone_priority") */
  public int $phonePriority;

  /** @Column(name="phone_stats") */
  public int $phoneStatus;

  /** @Column(name="phone_number") */
  public string $phoneNumber;

  /** @Column */
  public int $source;

  /** @Column */
  public DateTime $updated;

  /** @Column(name="phone_number_extension") */
  public string $extension;

  /**
   * @ManyToOne(targetEntity="App\Domain\Person\Person", inversedBy="phones")
   * @JoinColumn(name="person_id", referencedColumnName="person_id")
   */
  public Person $person;
}
