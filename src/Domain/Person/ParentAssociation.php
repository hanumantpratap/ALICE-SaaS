<?php
declare(strict_types=1);

namespace App\Domain\Person;

use DateTime;
use App\Domain\Student\Student;
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
class ParentAssociation {
  /** 
   * @Id
   * @ManyToOne(targetEntity="Person", inversedBy="studentAssociations", fetch="EAGER")
   * @JoinColumn(name="person_id", referencedColumnName="person_id")
   **/
  public ?Person $person;


  /** 
   * @Id
   * @ManyToOne(targetEntity="\App\Domain\Student\Student", inversedBy="studentAssociations")
   * @JoinColumn(name="student_id", referencedColumnName="id")
   **/
  private Student $student;

  /** @Column(name="association_type_id") */
  public int $associationTypeId;

  
  public function __construct(Student $student, Person $person, int $associationTypeId )
  {
      $this->student = $student;
      $this->person = $person;
      $this->associationTypeId = $associationTypeId;
  }


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