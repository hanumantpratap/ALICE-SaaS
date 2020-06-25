<?php
declare(strict_types=1);

namespace App\Domain\Person;

use DateTime;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\SequenceGenerator;

/**
 * @Entity
 * @Table(name="person_addresses", schema="public")
 */
class PersonAddress {
    /**
     * @Id
     * @GeneratedValue
     * @Column(name="address_id")
     * @SequenceGenerator(sequenceName="paddress_id_seq")
     */
    public ?int $addressId;

    /** @Column(name="person_id") */
    public int $personId;

    /** @Column(name="address_type") */
    public int $addressType;

    /** @Column(name="address_priority") */
    public int $addressPriority;

    /** @Column(name="address_status") */
    public int $addressStatus;

    /** @Column */
    protected int $source;

  /** @Column(name="updated", type="datetime") */
    public ?DateTime $updated;

    /** @Column */
    public ?string $address;

    /** @OneToOne(targetEntity="Person", inversedBy="address")
     * @JoinColumn(name="person_id", referencedColumnName="person_id")
     */
    protected ?Person $person;

    public function getPerson() {
        return $this->person;
    }

    public function setPerson(Person $person) {
        $this->person = $person;
    }

    public function getAddress() {
        return $this->address;
    }

    public function setAddress(string $address) {
        $this->address = $address;
    }

    public function __construct(?string $address) {
    $this->address = $address;
    $this->addressType = 1;
    $this->addressPriority = 1;
    $this->addressStatus = 1;
    $this->source = 1;
    $this->updated = new DateTime();
  }
}
