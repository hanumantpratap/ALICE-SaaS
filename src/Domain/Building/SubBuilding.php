<?php
declare(strict_types=1);

namespace App\Domain\Building;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\SequenceGenerator;

/**
 * @Entity
 * @Table(name="team_children", schema="prepared")
 */
class SubBuilding {
  /**
   * @Id
   * @GeneratedValue
   * @Column(name="tc_id")
   * @SequenceGenerator(sequenceName="prepared.tc_id_seq")
   */
  public ?int $id;

  /** 
   * @ManyToOne(targetEntity="Building", inversedBy="subBuildings")
   * @JoinColumn(name="team_id", referencedColumnName="team_id")
   **/
  public Building $parent;

  /** 
   * @ManyToOne(targetEntity="Building")
   * @JoinColumn(name="child_team_id", referencedColumnName="team_id")
   **/
  public Building $child;

  /** @Column(name="child_team_name") */
  private string $childName; // legacy column

  public function setParent(Building $parent) {
    $this->parent = $parent;
  }

  public function setChild(Building $child) {
    $this->child = $child;
    $this->childName = $child->getName();
  }
}
