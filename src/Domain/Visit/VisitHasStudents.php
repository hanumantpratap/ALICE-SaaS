<?php
declare(strict_types=1);

namespace App\Domain\Visit;

use DateTime;
use App\Domain\Student\Student;
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
 * @Table(name="visits_has_students", schema="visitor_management")
 */
class VisitHasStudents {
  /**
   * @Id
   * @ManyToOne(targetEntity="Visit", inversedBy="visitStudents")
   * @JoinColumn(name="visit_id", referencedColumnName="id")
   */
  protected Visit $visit;
  
  /**
   * @Id
   * @ManyToOne(targetEntity="\App\Domain\Student\Student")
   * @JoinColumn(name="student_id", referencedColumnName="id")
  */
  public ?Student $student;

  public function __construct(Visit $visit, Student $student)
  {
      $this->visit = $visit;
      $this->student = $student;
  }

  public function getVisit() {
    return $this->visit;
  }

  public function getStudent() {
    return $this->student;
  }

  public function setVisit(Visit $visit) {
    $this->visit = $visit;
  }

  public function setStudent(int $student) {
    $this->student = $student;
  }

}