<?php
declare(strict_types=1);

namespace App\Domain\Student;

use DateTime;
use App\Domain\Person\Person;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;

/**
 * @Entity
 * @Table(name="associations", schema="students")
 */
class StudentAssociation {
  /** 
   * @Id
   * @ManyToOne(targetEntity="Student", inversedBy="studentAssociations", fetch="EAGER")
   * @JoinColumn(name="student_id", referencedColumnName="id")
   **/
  public ?Student $student;


  /** 
   * @Id
   * @ManyToOne(targetEntity="\App\Domain\Person\Person", inversedBy="studentAssociations")
   * @JoinColumn(name="person_id", referencedColumnName="person_id")
   **/
  private Person $person;

  /** @Column(name="association_type_id") */
  public int $associationTypeId;


  public function getStudent() {
    return $this->student;
  }

  public function setStudent(Student $student) {
    $this->student = $student;
  }

  public function getPerson() {
    return $this->person;
  }

  public function setPerson(Person $person) {
    $this->person = $person;
  }

  public function getAssociationType() {
    return $this->associationType;
  }

  public function setAssociationType(AssociationType $associationType) {
    $this->associationType = $associationType;
  }

  public function setAssociationTypeId(int $associationTypeId) {
    $this->associationTypeId = $associationTypeId;
  }
}