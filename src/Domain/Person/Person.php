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
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;

/**
 * @Entity
 * @Table(name="people", schema="public")
 */
class Person {
  /**
   * @Id
   * @GeneratedValue
   * @Column(name="person_id")
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

  /** @OneToOne(targetEntity="PersonName", mappedBy="person") */
  public ?PersonName $name;

  /** @OneToMany(targetEntity="PersonPhone", mappedBy="person") */
  protected Collection $phones;

  /** @OneToOne(targetEntity="PersonEmail", mappedBy="person") */
  public ?PersonEmail $email;

  public function __construct() {
    $this->name = new PersonName();
    $this->phones = new ArrayCollection();
  }

  public function getDisplayName() {
    return $this->displayName;
  }

  public function getName() {
    return $this->name;
  }

  public function getEmail() {
    return $this->email;
  }
}