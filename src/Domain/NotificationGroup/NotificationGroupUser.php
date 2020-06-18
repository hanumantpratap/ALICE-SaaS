<?php
declare(strict_types=1);

namespace App\Domain\NotificationGroup;

use App\Domain\User\User;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Id;

/**
 * @Entity
 * @Table(name="notification_groups_has_users", schema="visitor_management")
 */
class NotificationGroupUser {

  /** @Column(name="notification_group_id") */
  public ?int $notificationGroupId;

  /** 
   * @Id
   * @ManyToOne(targetEntity="NotificationGroup", inversedBy="users")
   * @JoinColumn(name="notification_group_id", referencedColumnName="id")
   **/
  protected NotificationGroup $notificationGroup;

  /** @Column(name="user_id") */
  public ?int $userId;

  /** 
   * @Id
   * @ManyToOne(targetEntity="\App\Domain\User\User", inversedBy="notificationGroupUsers")
   * @JoinColumn(name="user_id", referencedColumnName="pa_id")
   **/
  protected User $user;

  /**
   * @Id 
   * @Column(name="building_id") */
  public ?int $buildingId;

  /** @Column(name="email", type="boolean") */
  public bool $email;
  
  /** @Column(name="text", type="boolean") */
  public bool $text;

  public function setNotificationGroup(NotificationGroup $notificationGroup) {
    $this->notificationGroup = $notificationGroup;
  }

  public function getUser() {
    return $this->user;
  }

  public function setUser(User $user) {
    $this->user = $user;
  }

  public function setBuildingId(int $buildingId) {
    $this->buildingId = $buildingId;
  }

  public function getBuildingId() {
    return $this->buildingId;
  }

  public function setEmail(bool $email) {
    $this->email = $email;
  }

  public function setText(bool $text) {
    $this->text = $text;
  }

  public function __construct() {
    $this->email = true;
    $this->text = true;
  }
}
