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
 * @Table(name="visits_has_people", schema="visitor_management")
 */
class VisitHasPeople {
  /**
   * @Id
   * @ManyToOne(targetEntity="Visit", inversedBy="visitPeople")
   * @JoinColumn(name="visit_id", referencedColumnName="id")
   */
  protected Visit $visit;
  
  /**
   * @Id
   * @ManyToOne(targetEntity="\App\Domain\Person\Person")
   * @JoinColumn(name="person_id", referencedColumnName="person_id")
  */
  public ?Person $person;

  public function __construct(Visit $visit, Person $person)
  {
      $this->visit = $visit;
      $this->person = $person;
  }

  public function getVisit() {
    return $this->visit;
  }

  public function getPerson() {
    return $this->person;
  }

  public function setVisit(Visit $visit) {
    $this->visit = $visit;
  }

  public function setPerson(int $person) {
    $this->person = $person;
  }

}