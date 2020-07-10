<?php
declare(strict_types=1);

namespace App\Domain\Visit;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;

/**
 * @Entity
 * @Table(name="reasons", schema="visitor_management")
 */
class VisitReason {
  /**
   * @Id
   * @GeneratedValue
   * @Column(name="id")
   */
  public ?int $id;
  
  /** @Column(name="type") */
  public ?string $type;

  /** @Column(name="inactive", type="boolean") */
  public ?bool $inactive;

  public function getId() {
    return $this->id;
  }

  public function getType() {
    return $this->type;
  }

  public function setType(String $type) {
    $this->type = $type;
  }

  public function getInactive() {
    return $this->inactive;
  }

  public function setInactive(bool $inactive) {
    $this->inactive = $inactive;
  }

  public function __construct() {
    $this->inactive = false;
  }

}