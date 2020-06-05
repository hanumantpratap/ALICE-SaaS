<?php
declare(strict_types=1);

namespace App\Domain\NotificationGroup;

use App\Domain\User\User;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;

/**
 * @Entity
 * @Table(name="notification_groups", schema="visitor_management")
 */
class NotificationGroup {
  /**
   * @Id
   * @GeneratedValue
   * @Column(name="id")
   */
  public ?int $id;

  /** @Column */
  public string $name;

  /** @Column */
  public ?description $description;

  /** @Column(name="force_text", type="boolean") */
  public ?bool $forceText;

  /** @Column(name="force_email", type="boolean") */
  public ?bool $forceEmail;

  /** @OneToMany(targetEntity="NotificationGroupUser", mappedBy="notificationGroup", cascade={"persist", "remove"}) */
  protected Collection $users;

  public function getGroupUsers() {
    return $this->users;
  }

  public function getUsersByBuildingId(int $buildingId) {
    $users = new ArrayCollection();

    foreach ($this->users as $user) {
      if ($user->getBuildingId() == $buildingId) {
        $users->add($user->getUser());
      }
    }

    return $users;
  }

  public function addUser(User $user, int $buildingId) {
    $notificationGroupUser = new NotificationGroupUser();
    $notificationGroupUser->setNotificationGroup($this);
    $notificationGroupUser->setUser($user);
    $notificationGroupUser->setBuildingId($buildingId);
    $this->users->add($notificationGroupUser);
  }

  public function __construct() {
    $this->description = null;
    $this->forceText = false;
    $this->forceEmail = false;
    $this->users = new ArrayCollection();
  }
}
