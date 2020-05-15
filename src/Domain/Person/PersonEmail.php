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

/**
 * @Entity
 * @Table(name="person_email", schema="public")
 */
class PersonEmail {
  /**
   * @Id
   * @GeneratedValue
   * @Column(name="email_id")
   */
  public ?int $id;

  /** @Column(name="person_id") */
  public int $personId;

  /** @Column(name="email_type") */
  public int $emailType = 1;

  /** @Column(name="email_priority") */
  public int $emailPriority = 1;

  /** @Column(name="email_status") */
  public int $emailStatus = 1;

  /** @Column(name="email_address") */
  public string $emailAddress;

  /** @Column */
  public ?int $source;

  /** @Column */
  public string $updated;

  /**
   * @OneToOne(targetEntity="Person", inversedBy="email")
   * @JoinColumn(name="person_id", referencedColumnName="person_id")
   */
  protected Person $person;

}
