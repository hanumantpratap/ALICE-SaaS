<?php
declare(strict_types=1);

namespace App\Domain\Visit;

use DateTime;
use App\Domain\Person\Person;
use App\Domain\User\User;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\DBAL\Types\VarDateTimeType;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

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

}