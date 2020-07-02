<?php
declare(strict_types=1);

namespace App\Domain\Student;

use DateTime;
use App\Domain\Person\Person;
use App\Domain\Person\ParentAssociation;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use JsonSerializable;

/**
 * @Entity
 * @Table(name="students", schema="respond")
 */
class Student implements JsonSerializable{
  /**
   * @Id
   * @GeneratedValue
   * @Column(name="id")
   */
  public ?int $id = null;

  /** @Column(name="first_name") */
  public string $firstName;

  /** @Column(name="last_name") */
  public string $lastName;

  /** @Column(name="middle_initial") */
  public ?string $middleInitial;

  /** @Column */
  public ?string $suffix;

  /** @Column(name="student_number") */
  public ?string $studentNumber;

  /** @Column */
  public ?string $gender;

   /** @Column(name="dob", nullable=true, type="datetime") */
  public ?DateTime $dob;

  /** @Column */
  public ?string $grade;

  /** @Column(name="inactive", type="boolean") */
  public ?bool $inactive;


  // populated only when retrieving an individual student through ViewStudentAction.php
 
  /** @OneToMany(targetEntity="\App\Domain\Person\ParentAssociation", mappedBy="student", cascade={"persist", "remove"}, orphanRemoval=true) */
  private Collection $parentAssociations;

  public ?array $parentAssociationArray = null;

  
  public function getId() {
    return $this->id;
  }

  public function getParentAssociations() {
    if( $this->parentAssociationArray === null ) {
      $this->parentAssociationArray = array();

      foreach($this->parentAssociations as $parentAssociation) {
        $parentAssociation->getPerson();
        $this->parentAssociationArray[] = $parentAssociation;
      }
    }

    return $this->parentAssociationArray;
  }


  public function addParentAssociation(Person $person, int $associationTypeId ) {
    $parentAssociation = new ParentAssociation( $this, $person, $associationTypeId );
    $this->parentAssociations->add($parentAssociation);
  }

  public function jsonSerialize() {
      return [
        'id' => $this->id,
        'firstName' => $this->firstName,
        'lastName' => $this->lastName,
        'middleInitial' => $this->middleInitial,
        'suffix' => $this->suffix,
        'studentNumber' => $this->studentNumber,
        'gender' => $this->gender,
        'dob' => $this->dob,
        'grade' => $this->grade,
        'inactive' => $this->inactive,
        'parentAssociationArray' => $this->parentAssociationArray
      ];
  }

}