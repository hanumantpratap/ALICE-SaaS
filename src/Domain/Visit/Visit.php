<?php
declare(strict_types=1);

namespace App\Domain\Visit;

use DateTime;
use App\Domain\Person\Person;
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
  private ?string $identificationId;

  /** @Column(name="reason_id") */
  private ?int $reasonId;

  /** @Column(name="visitor_type_id") */
  private ?int $visitorTypeId;

  /*******  For now, setting reasons/visitor_types to simple text fields, but will eventually use the above ids and join them */

  /** @Column */
  public ?string $reason;

  /** @Column(name="visitor_type") */
  public ?string $visitorType;
  
  /** @Column */
  public ?string $notes;

  /** @Column(name="requires_approval", type="boolean")*/
  public ?bool $requiresApproval;

  /** @Column(name="approved", type="boolean")*/
  public ?bool $approved;

  /** @Column(name="approved_by")*/
  public ?int $approvedBy;

   /** @Column(name="security_alerted", type="boolean") */
  public ?bool $securityAlerted;

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

  /** @OneToMany(targetEntity="VisitBadge", mappedBy="visit", cascade={"persist", "remove"}) */
  protected Collection $badges;

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

  public function getBuildingId() {
    return $this->buildingId;
  }

  public function setBuildingId(int $buildingId) {
    $this->buildingId = $buildingId;
  }

  public function setNotes(string $notes) {
    $this->notes = $notes;
  }

  public function getReason() {
    return $this->reason;
  }

  public function setReason(string $reason) {
    $this->reason = $reason;
  }

  public function getVisitorType() {
    return $this->visitorType;
  }

  public function setVisitorType(string $visitorType) {
    $this->visitorType = $visitorType;
  }

  public function getVisitor() {
    $person = $this->getPerson();

    $visitor = new \stdClass();
    $visitor->personId = $person->personId;
    
    $visitor->firstName = $person->getName()->getGivenName();
    $visitor->lastName = $person->getName()->getFamilyName();
    $visitor->emailAddress = $person->getEmail()->getEmailAddress();

    $demographics = $person->getDemographics();
    $visitor->birthDate = $demographics ? $demographics->getBirthDate() : null;

    $address = $person->getAddress();
    $visitor->address = $address ? $address->getAddress() : null;

    $visitorSettings = $person->getVisitorSettings();
    $visitor->picture = $visitorSettings ? $visitorSettings->getPicture() : null;

    $visitor->blacklist = $person->getBlacklist()->filter(function ($item) {
      return $item->getBuildingId() == $this->getBuildingId();
    })->first() ?: null;

    return $visitor;
  }

  public function getBadges(): Collection {
    return $this->badges;
  }

  protected function setBadges(Collection $badges): void {
    $this->badges = $badges;
  }

  public function addBadge(VisitBadge $badge): void {
    $this->badges->add($badge);
  }

  public function removeBadge(VisitBadge $badge): void {
    $this->badges->removeElement($badge);
  }

  public function __construct() {
    $this->dateCreated = new DateTime();
    $this->buildingId = 5240;
    $this->requiresApproval = false;
    $this->approved = false;
    $this->securityAlerted = false;
    $this->checkIn = null;
    $this->checkOut = null;
    $this->estimatedCheckIn = null;
    $this->estimatedCheckOut = null;
    $this->badges = new ArrayCollection();
  }
}
