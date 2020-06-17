<?php
declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\Person\Person;
use DateTime;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\SequenceGenerator;

/**
 * @Entity
 * @Table(name="person_account", schema="public")
 */
class User {
  /**
   * @Id
   * @GeneratedValue
   * @SequenceGenerator(sequenceName="pa_id_seq")
   * @Column(name="pa_id")
   */
  public ?int $id;

  /** @Column(name="person_id") */
  public int $personId;

  /** @Column */
  public string $login;

  /** @Column(name="password") */
  public ?string $password;

  /** @Column(name="regkey_id") */
  public int $regKeyId;

  /** @Column(name="reg_date", type="datetime") */
  public DateTime $regDate;

  /** @Column(name="reg_ip") */
  public string $regIp;

  /** @Column(name="agree_tos") */
  public ?int $agreedTOS;

  /** @Column(name="account_type") */
  public ?string $accountType;

  /** @Column(name="primary_team_id") */
  public ?int $primaryTeamId;

  /** @Column(name="access_type") */
  public ?int $accessType;

  /** @Column(name="last_updated") */
  public ?string $lastUpdated;

  /** @Column(name="global_user_id") */
  public ?int $globalUserId;

  /** @Column(name="visitor_management_access", type="boolean") */
  public ?bool $vmAccess;

  /**
   * @OneToOne(targetEntity="\App\Domain\Person\Person", fetch="EAGER", cascade={"persist", "remove"})
   * @JoinColumn(name="person_id", referencedColumnName="person_id")
   */
  public Person $person;

  /** 
   * @OneToMany(targetEntity="\App\Domain\NotificationGroup\NotificationGroupUser", mappedBy="user") 
   */
  protected Collection $notificationGroups;

  public array $notificationGroupsList;

  public function getId() {
    return $this->id;
  }
  
  public function getLogin() {
    return $this->login;
  }

  public function setLogin(string $login) {
    $this->login = $login;
  }

  public function getPerson() {
    return $this->person;
  }

  public function setPerson(Person $person) {
    $this->person = $person;
  }

  // force user to load person object
  public function loadPerson() {
    $load = $this->getPerson()->getDisplayName();
  }

  public function getFirstName() {
    return $this->person->getName()->getGivenName();
  }

  public function getLastName() {
    return $this->person->getName()->getFamilyName();
  }

  public function getNotificationGroups() {
    return $this->notificationGroups;
  }

  /* public function addNotificationGroup(NotificationGroup $notificationGroup) {
    $notificationGroup->addUser($this);
    $this->notificationGroups->add($notificationGroup);
  } */

  public function getPrimaryTeamId() {
    return $this->primaryTeamId;
  }

  public function getGlobalUserId() {
    return $this->globalUserId;
  }

  public function setGlobalUserId(int $globalUserId) {
    $this->globalUserId = $globalUserId;
  }

  public function canAccessVm() {
    return $this->vmAccess;
  }

  public function enable() {
    $this->vmAccess = true;
  }

  public function disable() {
    $this->vmAccess = false;
  }

  public function __construct() {
    $this->notificationGroups = new ArrayCollection();
    $this->renderNotificationGroups = false;
    $this->accessType = 2;
    $this->regKeyId = 1;
    $this->regDate = new DateTime();
    $this->regIp = $_SERVER['REMOTE_ADDR'];
    $this->password = "NO_PASSWORD";
    $this->vmAccess = true;
  }
}
