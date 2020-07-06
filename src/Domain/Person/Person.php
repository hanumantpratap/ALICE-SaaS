<?php
declare(strict_types=1);

namespace App\Domain\Person;

use App\Domain\Student\Student;
use App\Domain\Student\StudentAssociation;
use App\Domain\SexOffender\SexOffenderMatch;
use App\Domain\SexOffender\SexOffenderNonMatch;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\SequenceGenerator;

/**
 * @Entity
 * @Table(name="people", schema="public")
 */
class Person {
  /**
   * @Id
   * @GeneratedValue
   * @Column(name="person_id")
   * @SequenceGenerator(sequenceName="person_id_seq")
   */
  public ?int $personId = null;

  /** @Column(name="status") */
  public int $status;

  /** @Column(name="display_name") */
  public ?string $displayName;

  /** @Column(name="external_id") */
  public ?string $externalId;

  /** @Column(name="type") */
  public ?string $type;

  /** @OneToOne(targetEntity="PersonName", mappedBy="person", cascade={"persist", "remove"}) */
  public ?PersonName $name;

  /** @OneToOne(targetEntity="PersonDemographics", mappedBy="person", cascade={"persist", "remove"}) */
  public ?PersonDemographics $demographics;

  /** @OneToMany(targetEntity="PersonPhone", mappedBy="person", cascade={"persist", "remove"}, orphanRemoval=true) */
  protected Collection $phones;

  /** @OneToOne(targetEntity="PersonEmail", mappedBy="person", cascade={"persist", "remove"}) */
  public ?PersonEmail $email = null;

  /** @OneToMany(targetEntity="Flag", mappedBy="person") */
  protected Collection $flags;

  /** @OneToMany(targetEntity="BlacklistItem", mappedBy="person", cascade={"persist", "remove"}) */
  protected Collection $blacklist;

  public array $blacklistArray;
  
  /** @OneToMany(targetEntity="Identification", mappedBy="person", cascade={"persist", "remove"}) */
  protected Collection $identifications;

  /** @OneToOne(targetEntity="PersonAddress", mappedBy="person", cascade={"persist", "remove"}) */
  public ?PersonAddress $address;

  /** @OneToOne(targetEntity="VisitorSettings", mappedBy="person", cascade={"persist", "remove"}) */
  public ?VisitorSettings $visitorSettings;

  /** @OneToMany(targetEntity="\App\Domain\Visit\Visit", mappedBy="person", cascade={"persist", "remove"}) */
  protected Collection $visits;

  /** @OneToMany(targetEntity="Note", mappedBy="person", cascade={"persist", "remove"}) */
  protected Collection $notes;

  /** @OneToMany(targetEntity="\App\Domain\Student\StudentAssociation", mappedBy="person", cascade={"persist", "remove"}, orphanRemoval=true) */
  protected Collection $studentAssociations;

  /** @OneToOne(targetEntity="\App\Domain\SexOffender\SexOffenderMatch", mappedBy="person", cascade={"persist", "remove"}) */
  public ?SexOffenderMatch $sexOffenderMatch;

  /** @OneToMany(targetEntity="\App\Domain\SexOffender\SexOffenderNonMatch", mappedBy="person", cascade={"persist", "remove"}, orphanRemoval=true) */
  protected Collection $sexOffenderNonMatches;

  public function getVisits() {
    return $this->visits->toArray();
  }

  public function getPersonId() {
    return $this->personId;
  }

  public function getStatus() {
    return $this->status;
  }

  public function setStatus(int $status) {
    $this->status = $status;
  }

  public function getDisplayName() {
    return $this->displayName;
  }

  public function getType() {
    return $this->type;
  }

  public function setType(string $type) {
    $this->type = $type;
  }

  public function getName() {
    return $this->name;
  }

  public function setName(PersonName $name) {
    $this->name = $name;
    $this->displayName = $name->getFamilyName() . ', ' . $name->getGivenName();
  }
  
  public function getEmail() {
    return $this->email;
  }

  public function setEmail(PersonEmail $email) {
    $email->setPerson($this);
    $this->email = $email;
  }
  
  public function getBlacklist(): Collection {
    return $this->blacklist;
  }

  protected function setBlacklist(Collection $blacklist): void {
    $this->blacklist = $blacklist;
  }

  public function addBlacklistItem(BlacklistItem $item): void {
    $this->blacklist->add($item);
  }

  public function removeBlacklistItem(BlacklistItem $item): void {
    $this->blacklist->removeElement($item);
  }

  public function isOnBuildingBlacklist(int $buildingId): bool {
    return $this->blacklist->exists(fn($key, $value) => $value->buildingId == $buildingId);
  }

  public function addNote(Note $note): void {
    $this->notes->add($note);
  }

  public function removeNote(Note $note): void {
    $this->notes->removeElement($note);
  }

  public function updateNote(Note $note): void {
    $this->notes->set($note->id, $note);
  }

  public function getNoteById(int $id): Note {
    return $this->notes->filter(fn($value) => $value->id == $id)->first();
  }

  public function getNotes(): array {
    return $this->notes->toArray();
  }

  public function getIdentifications() {
    return $this->identifications;
  }

  public function addIdentification(Identification $identification) {
    $identification->setPerson($this);
    $this->identifications->add($identification);
  }

  public function removeIdentification(Identification $identification): void {
    $this->identifications->removeElement($identification);
  }

  public function getDemographics() {
    return $this->demographics;
  }

  public function getAddress() {
    return $this->address;
  }

  public function setAddress(PersonAddress $address) {
    $address->setPerson($this);
    $this->address = $address;
  }
  
  public function getVisitorSettings() {
    return $this->visitorSettings;
  }

  public function setVisitorSettings(VisitorSettings $visitorSettings) {
    $this->visitorSettings = $visitorSettings;
  }

  public function getStudentAssociations() {
    return $this->studentAssociations;
  }

  public function addStudentAssociations(StudentAssociation $studentAssociation) {
    $this->studentAssociations->add($studentAssociation);
  }

  public function getStudents() {
    $students = new ArrayCollection();
    $studentAssociations = $this->getStudentAssociations();

    foreach($studentAssociations as $studentAssociation) {
      $students->add($studentAssociation->getStudent());
    }

    return $students;
  }

  public function addStudent(Student $student, int $associationTypeId) {
    $studentAssociation = new StudentAssociation();
    $studentAssociation->setPerson($this);
    $studentAssociation->setStudent($student);
    $studentAssociation->setAssociationTypeId($associationTypeId);
    $this->studentAssociations->add($studentAssociation);
  }

  public function removeStudent(Student $student) {
    $studentId = $student->getId();

    foreach($this->getStudentAssociations() as $studentAssociation) {
      if ($studentAssociation->getStudent()->getId() == $studentId) {
        $this->studentAssociations->removeElement($studentAssociation);
        return;
      }
    }
  }

  public function getPhones() {
    return $this->phones;
  }

  public function addPhone(PersonPhone $phone) {
    $phone->addPerson($this);
    $this->phones->add($phone);
  }

  public function getPhoneByType(int $type) {
    return $this->phones->filter(function ($phone) use ($type) {
      return $phone->getType() == $type;
    })->first() ?: null;
  }

  public function getSexOffenderMatch() {
    return $this->sexOffenderMatch;
  }

  public function setSexOffenderMatch(SexOffenderMatch $sexOffenderMatch) {
    $sexOffenderMatch->setPerson($this);
    $this->sexOffenderMatch = $sexOffenderMatch;
  }

  public function getSexOffenderNonMatches() {
    return $this->sexOffenderNonMatches;
  }

  public function addSexOffenderNonMatch(SexOffenderNonMatch $sexOffenderNonMatch) {
    $sexOffenderNonMatch->setPerson($this);
    $this->sexOffenderNonMatches->add($sexOffenderNonMatch);
  }

  public function pruneOffendersList(array $offenders) {
    $nonMatchesIx = [];
    $nonMatches = $this->getSexOffenderNonMatches()->toArray();

    foreach ($nonMatches as $nonMatch) {
      $nonMatchesIx[$nonMatch->getSexOffenderId()] = 1;
    }

    $return = [];
    foreach ($offenders as $offender) {
      if (!isset($nonMatchesIx[$offender->offenderid])) {
        $return[] = $offender; 
      }
    }

    return $return;
  }

  public function __construct() {
    $this->name = new PersonName();
    $this->name->setPerson($this);
    $this->demographics = new PersonDemographics();
    $this->demographics->setPerson($this);
    $this->visitorSettings = new VisitorSettings();
    $this->visitorSettings->setPerson($this);
    $this->phones = new ArrayCollection();
    $this->flags = new ArrayCollection();
    $this->blacklist = new ArrayCollection();
    $this->identifications = new ArrayCollection();
    $this->visits = new ArrayCollection();
    $this->notes = new ArrayCollection();
    $this->studentAssociations = new ArrayCollection();
    $this->sexOffenderNonMatches = new ArrayCollection();
    $this->address = null;
    $this->sexOffenderMatch = null;
  }
}