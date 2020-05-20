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
use Doctrine\ORM\Mapping\ManyToMany;
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

  /** @Column(name="force_text") */
  public ?bool $forceText;

  /** @Column(name="force_email") */
  public ?bool $forceEmail;

  /** 
   * @ManyToMany(targetEntity="\App\Domain\User\User", inversedBy="notificationGroups") 
   * @JoinTable(name="visitor_management.notification_groups_has_users",
   *    joinColumns={@JoinColumn(name="notification_groups_id", referencedColumnName="id")},
   *    inverseJoinColumns={@JoinColumn(name="user_id", referencedColumnName="pa_id")}
   *  )
   */
  protected Collection $users;

  public function getUsers() {
    return $this->users;
  }

  public function addUser(User $user) {
    $this->users->add($user);
  }

  public function __construct() {
    $this->description = null;
    $this->forceText = false;
    $this->forceEmail = false;
    $this->users = new ArrayCollection();
  }
}
