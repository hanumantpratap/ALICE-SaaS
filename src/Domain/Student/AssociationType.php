<?php
declare(strict_types=1);

namespace App\Domain\Student;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;

/**
 * @Entity
 * @Table(name="association_types", schema="students")
 */
class AssociationType {
  /**
   * @Id
   * @GeneratedValue
   * @Column
   */
  public ?int $id;

  /** @Column */
  public string $label;

  public function getId() {
    return $this->id;
  }

  public function getLabel() {
    return $this->label;
  }

  public function setLabel(string $label) {
    $this->label = $label;
  }
}