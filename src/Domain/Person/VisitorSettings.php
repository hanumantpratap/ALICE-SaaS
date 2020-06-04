<?php
declare(strict_types=1);

namespace App\Domain\Person;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\Id;

/**
 * @Entity
 * @Table(name="visitor_settings", schema="visitor_management")
 */
class VisitorSettings {
  /** 
   * @Id
   * @OneToOne(targetEntity="Person", inversedBy="visitorSettings")
   * @JoinColumn(name="person_id", referencedColumnName="person_id")
   **/
  public ?Person $person;

  /** @Column(name="email_notifications", type="boolean") */
  public bool $emailNotifications;

  /** @Column(name="text_notifications", type="boolean") */
  public bool $textNotifications;

  /** @Column */
  public ?string $picture;

  public function getPerson() {
    return $this->person;
  }

  public function setPerson(Person $person) {
    $this->person = $person;
  }

  public function getEmailNotifications() {
    return $this->emailNotifications;
  }

  public function setEmailNotifications(bool $emailNotifications) {
    $this->emailNotifications = $emailNotifications;
  }

  public function getTextNotifications() {
    return $this->textNotifications;
  }

  public function setTextNotifications(bool $textNotifications) {
    $this->textNotifications = $textNotifications;
  }

  public function getPicture() {
    return $this->picture;
  }

  public function setPicture(string $picture) {
    $this->picture = $picture;
  }

  public function __construct() {
    $this->emailNotifications = true;
    $this->textNotifications = true;
    $this->picture = null;
  }
}
