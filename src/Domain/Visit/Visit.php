<?php
declare(strict_types=1);

namespace App\Domain\Visit;

use DateTime;
use App\Domain\Person\Person;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\DBAL\Types\VarDateTimeType;

/**
 * @Entity
 * @Table(name="visits", schema="visitor_management")
 */
class Visit {
  /**
   * @Id
   * @GeneratedValue
   * @Column(name="id")
   */
  public ?int $id;

  /** @Column(name="person_id") */
  public int $personId;

  /** @Column(name="building_id") */
  public int $buildingId;

  /** @Column(name="user_id") */
  public ?int $userId;

  /** @Column(name="identification_id") */
  public ?int $identificationId;

  /** @Column(name="reason_id") */
  public ?int $reasonId;

  /** @Column(name="visitor_type_id") */
  public ?int $visitorTypeId;

  /** @Column */
  public ?string $notes;

  /** @Column(name="requires_approval")*/
  public ?string $requiresApproval;

  /** @Column */
  public ?string $approved;

  /** @Column(name="approved_by")*/
  public ?int $approvedBy;

   /** @Column(name="security_alerted") */
  public ?string $securityAlerted;

  /** @Column(name="date_created", nullable=true, type="datetime") */
  public ?DateTime $dateCreated;

  /** @Column(name="check_in", nullable=true, type="datetime") */
  public ?DateTime $checkIn;

  /** @Column(name="check_out", nullable=true, type="datetime") */
  public ?DateTime $checkOut;

  /** @Column(name="estimated_check_in", nullable=true, type="datetime") */
  public ?DateTime $estimatedCheckIn;

  /** @Column(name="estimated_check_out", nullable=true, type="datetime") */
  public ?DateTime $estimatedCheckOut;

  /**
   * @ManyToOne(targetEntity="\App\Domain\Person\Person")
   * @JoinColumn(name="person_id", referencedColumnName="person_id")
   */
  protected ?Person $person;

  public object $visitor;

  public function getId() {
    return $this->id;
  }

  public function getPerson() {
    return $this->person;
  }

  public function setPerson(Person $person) {
    $this->person = $person;
  }

  public function setUserId(int $userId) {
    $this->userId = $userId;
  }

  public function setBuildingId(int $buildingId) {
    $this->buildingId = $buildingId;
  }

  public function setNotes(string $notes) {
    $this->notes = $notes;
  }

  public function getVisitor() {
    $person = $this->getPerson();

    $visitor = new \stdClass();
    $visitor->personId = $person->personId;
    
    $visitor->firstName = $person->getName()->givenName;
    $visitor->lastName = $person->getName()->familyName;
    $visitor->emailAddress = $person->getEmail()->emailAddress;

    return $visitor;
  }

  public function __construct() {
    $this->dateCreated = new DateTime();
    $this->buildingId = 5240;
    $this->requiresApproval = "false";
    $this->approved = "false";
    $this->securityAlerted = "false";
    $this->checkIn = null;
    $this->checkOut = null;
    $this->estimatedCheckIn = null;
    $this->estimatedCheckOut = null;
  }
}
