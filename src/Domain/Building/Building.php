<?php
declare(strict_types=1);

namespace App\Domain\Building;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;

/**
 * @Entity
 * @Table(name="teams", schema="prepared")
 */
class Building {
  /**
   * @Id
   * @GeneratedValue
   * @Column(name="team_id")
   */
  public ?int $id;

  /** @Column(name="team_name") */
  public ?string $name;

  /** @Column(name="team_address") */
  public ?string $address;

  /** @Column(name="team_city") */
  public ?string $city;

  /** @Column(name="team_state") */
  public ?string $state;

  /** @Column(name="team_zip") */
  public ?string $zip;

  /** @Column(name="team_county") */
  public ?string $county;

  /** @Column(name="team_level") */
  private int $level;

  /** @Column */
  public bool $active;

  public function getName() {
    return $this->name;
  }
}
