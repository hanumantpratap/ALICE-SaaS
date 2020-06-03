<?php
declare(strict_types=1);

namespace App\Domain\Student;

use DateTime;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\Common\Collections\Collection;

/**
 * @Entity
 * @Table(name="students", schema="respond")
 */
class Student {
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

  /** @OneToMany(targetEntity="StudentAssociation", mappedBy="student", cascade={"persist", "remove"}) */
  protected Collection $studentAssociations;
  
  public function getId() {
    return $this->id;
  }
}