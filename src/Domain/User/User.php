<?php
declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\Person\Person;
use App\Domain\NotificationGroup\NotificationGroup;
use App\Domain\NotificationGroup\NotificationGroupUser;
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
  private string $login;

  /** @Column(name="password") */
  private ?string $password;

  /** @Column(name="regkey_id") */
  private int $regKeyId;

  /** @Column(name="reg_date", type="datetime") */
  public DateTime $regDate;

  /** @Column(name="reg_ip") */
  private string $regIp;

  /** @Column(name="agree_tos") */
  private ?int $agreedTOS;

  /** @Column(name="account_type") */
  private ?string $accountType;

  /** @Column(name="primary_team_id") */
  public ?int $primaryBuildingId;

  /** @Column(name="access_type") */
  public ?int $accessType;

  /** @Column(name="last_updated") */
  public ?string $lastUpdated;

  /** @Column(name="global_user_id") */
  public ?int $globalUserId;

  /** @Column(name="visitor_management_access", type="boolean") */
  public ?bool $vmAccess;

  /** @Column(name="visitor_management_role") */
  public ?string $role;

  /**
   * @OneToOne(targetEntity="\App\Domain\Person\Person", cascade={"persist", "remove"}, fetch="EAGER")
   * @JoinColumn(name="person_id", referencedColumnName="person_id")
   */
  public Person $person;

  /**
   * @OneToMany(targetEntity="\App\Domain\NotificationGroup\NotificationGroupUser", mappedBy="user", cascade={"persist", "remove"}, orphanRemoval=true)
   */
  protected Collection $notificationGroupUsers;

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

public function addNotificationGroup(NotificationGroup $notificationGroup, int $buildingId, bool $email, bool $text) {
    $notificationGroupUser = new NotificationGroupUser();
    $notificationGroupUser->setUser($this);
    $notificationGroupUser->setNotificationGroup($notificationGroup);
    $notificationGroupUser->setBuildingId($buildingId);
    $notificationGroupUser->setEmail($email);
    $notificationGroupUser->setText($text);
    $this->notificationGroupUsers->add($notificationGroupUser);
}

public function clearNotificationGroups() {
    $this->notificationGroupUsers->clear();
}

public function notificationGroupUsers() {
    return $this->notificationGroupUsers;
}

public function getPrimaryBuildingId() {
    return $this->primaryBuildingId;
}

public function setPrimaryBuildingId(int $primaryBuildingId) {
    $this->primaryBuildingId = $primaryBuildingId;
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

public function getRole() {
    return $this->role;
}

public function setRole(string $role) {
    $this->role = $role;
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
