<?php
declare(strict_types=1);

namespace App\Domain\User;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;

/**
 * @Entity
 * @Table(name="person_account", schema="public")
 */
class User {
  /**
   * @Id
   * @GeneratedValue
   * @Column(name="pa_id")
   */
  public ?int $id;

  /** @Column(name="person_id") */
  public int $personId;

  /** @Column */
  public string $login;

  /** @Column(name="password") */
  public ?string $password;

  /** @Column(name="regkey_id") */
  public int $regKeyId = 1;

  /** @Column(name="agree_tos") */
  public ?int $agreedTOS;

  /** @Column(name="account_type") */
  public ?string $accountType;

  /** @Column(name="primary_team_id") */
  public ?int $primaryTeamId;

  /** @Column(name="access_type") */
  public ?int $accessType;

  /** @Column(name="last_updated") */
  public ?string $lastUpdated;

  /** @Column(name="global_user_id") */
  public ?int $globalUserId;

  /**
   * @OneToOne(targetEntity="\App\Domain\Person\Person")
   * @JoinColumn(name="person_id", referencedColumnName="person_id")
   */
  public \App\Domain\Person\Person $person;

  public function getPerson() {
    return $this->person;
  }

  // force user to load person object
  public function loadPerson() {
    $load = $this->getPerson()->getDisplayName();
  }
}
