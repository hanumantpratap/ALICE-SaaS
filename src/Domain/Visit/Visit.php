<?php
declare(strict_types=1);

namespace App\Domain\Visit;

use DateTime;
use App\Domain\Building\Building;
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

  /**
   * @ManyToOne(targetEntity="\App\Domain\Building\Building", fetch="EAGER")
   * @JoinColumn(name="building_id", referencedColumnName="team_id")
   */
  public ?Building $building;

  /** @Column(name="user_id") */
  public ?int $userId;

  /** @Column(name="identification_id") */
  private ?string $identificationId;

  /** @Column(name="reason_id") */
  private ?int $reasonId;

  /**
  * @ManyToOne(targetEntity="\App\Domain\Visit\VisitReason")
  * @JoinColumn(name="reason", referencedColumnName="id")
  */
  protected VisitReason $reason;

  /** @Column(name="visitor_type_id") */
  private ?int $visitorTypeId;

  /**
  * @ManyToOne(targetEntity="\App\Domain\Visit\VisitorType")
  * @JoinColumn(name="visitor_type", referencedColumnName="id")
  */
  protected VisitorType $visitorType;

  /** @Column */
  public ?string $notes;

  /** @Column(name="requires_approval", type="boolean")*/
  public ?bool $requiresApproval;

  /** @Column(name="approved", type="boolean")*/
  public ?bool $approved;

  /** @Column(name="approved_by")*/
  public ?int $approvedBy;
  /**
   * @ManyToOne(targetEntity="\App\Domain\User\User")
   * @JoinColumn(name="approved_by", referencedColumnName="pa_id")
   */
  protected ?User $approvedByUser;

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

  /** @Column(name="checked_out_by") */
  public ?int $checkedOutBy;

  /** @OneToMany(targetEntity="VisitBadge", mappedBy="visit", cascade={"persist", "remove"}) */
  protected Collection $badges;

  public array $badgeArray;

  /**
   * @ManyToOne(targetEntity="\App\Domain\Person\Person")
   * @JoinColumn(name="person_id", referencedColumnName="person_id")
   */
  protected ?Person $person;

  public object $visitor;

  public function getId() {
    return $this->id;
  }

  public function getApprovedByUser() {
    return $this->approvedByUser;
  }

  public function setApprovedByUser(User $approvedByUser) {
    $this->approvedByUser = $approvedByUser;
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

  public function setBuilding(Building $building) {
    $this->building = $building;
  }

  public function setNotes(string $notes) {
    $this->notes = $notes;
  }

  public function getReason() {
    return $this->reason;
  }

  public function setReason(VisitReason $reason) {
    $this->reason = $reason;
  }

  public function getVisitorType() {
    return $this->visitorType;
  }

  public function setVisitorType(VisitorType $visitorType) {
    $this->visitorType = $visitorType;
  }

  public function setSecurityAlerted(bool $securityAlerted) {
    $this->securityAlerted = $securityAlerted;
  }

  public function getBasicVisitorInfo() {
    $person = $this->getPerson();

    $visitor = new \stdClass();
    $visitor->personId = $person->personId;
    $visitor->firstName = $person->getName()->getGivenName();
    $visitor->lastName = $person->getName()->getFamilyName();

    return $visitor;
  }

  public function getVisitor() {
    $person = $this->getPerson();

    $visitor = new \stdClass();
    $visitor->personId = $person->personId;

    $visitor->firstName = $person->getName()->getGivenName();
    $visitor->lastName = $person->getName()->getFamilyName();

    $email = $person->getEmail();
    $visitor->emailAddress = $email ? $email->getEmailAddress() : null;

    $demographics = $person->getDemographics();
    $visitor->birthDate = $demographics ? $demographics->getBirthDate() : null;

    $address = $person->getAddress();
    $visitor->address = $address ? $address->getAddress() : null;

    $visitorSettings = $person->getVisitorSettings();
    $visitor->picture = $visitorSettings ? $visitorSettings->getPicture() : null;

    $visitor->blacklist = $person->getBlacklist()->filter(function ($item) {
      return $item->getBuildingId() == $this->getBuildingId();
    })->first() ?: null;

    $students = $person->getStudents();
    $visitor->students = $students->isEmpty() ? null : $students->toArray();

    $visitor->notes = $person->getNotes();

    return $visitor;
  }

  public function setCheckIn($datetime) {
      if ($datetime instanceof DateTime) {
          $this->checkIn = $datetime;
      }
  }

  public function getBadgeList(): array {
      return $this->badges->map(function($badge) {
          return $badge->printedAt;
      })->toArray();
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
