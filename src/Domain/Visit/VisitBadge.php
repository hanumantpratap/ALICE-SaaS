<?php
declare(strict_types=1);

namespace App\Domain\Visit;

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
 * @Table(name="visits_has_badge", schema="visitor_management")
 */
class VisitBadge {
  /**
   * @Id
   * @GeneratedValue
   * @Column
   */
  public ?int $id;

  /** @Column(name="visit_id") */
  public int $visitId;

  /** @Column(name="date_printed", nullable=false, type="datetime") */
  public ?DateTime $printedAt;

  /**
   * @ManyToOne(targetEntity="Visit", inversedBy="badges")
   * @JoinColumn(name="visit_id", referencedColumnName="id")
   */
  protected Visit $visit;

  public function setVisit(Visit $visit): void {
    $this->visit = $visit;
  }

  public function __construct() {
    $this->printedAt = new DateTime();
  }
}
