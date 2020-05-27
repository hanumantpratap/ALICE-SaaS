<?php
declare(strict_types=1);

namespace App\Domain\Person;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\Id;

/**
 * @Entity
 * @Table(name="identification", schema="visitor_management")
 */
class Identification {
  /** 
   * @Id
   * @Column(name="id")
   **/
  public string $id;
  
  /** @Column(name="person_id") */
  public int $personId;

  /**
   * @ManyToOne(targetEntity="Person", inversedBy="identifications")
   * @JoinColumn(name="person_id", referencedColumnName="person_id")
   */
  protected Person $person;

  public function getId() {
    return $this->id;
  }

  public function setId(string $id) {
    $this->id = $id;
  }
  
  public function getPersonID() {
    return $this->person;
  }

  public function getPerson() {
    return $this->person;
  }

  public function setPerson(Person $person) {
    $this->person = $person;
  }
}
