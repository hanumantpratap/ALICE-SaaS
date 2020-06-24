<?php
declare(strict_types=1);

namespace App\Domain\Person;

use DateTime;
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
 * @Table(name="person_demographics", schema="public")
 */
class PersonDemographics {
    /**
     * @Id
     * @GeneratedValue
     * @Column(name="pd_id")
     * @SequenceGenerator(sequenceName="pd_id_seq")
     */
    public ?int $pdId;

    /** @Column(name="gender") */
    public ?int $gender;

    /** @Column(name="birth_date", type="datetime") */
    public ?DateTime $birthDate;

    /** @Column(name="ethnicity") */
    public ?string $ethnicity;

    /** @Column(name="blood_type") */
    public ?int $bloodType;

    /** @Column(name="marital_status") */
    public ?int $maritalStatus;

    /** @Column(name="eye_color") */
    public ?int $eyeColor;

    /** @Column(name="hair_color") */
    public ?int $hairColor;

    /** @Column(name="marks") */
    public ?string $marks;

    /** @Column(name="height") */
    public ?int $height;

    /** @Column(name="weight") */
    public ?int $weight;

    /** @OneToOne(targetEntity="Person", inversedBy="demographics")
     * @JoinColumn(name="person_id", referencedColumnName="person_id")
     */
    protected ?Person $person;

    public function getPerson() {
        return $this->person;
    }

    public function setPerson(Person $person) {
        $this->person = $person;
    }

    public function getBirthDate() {
        return $this->birthDate;
    }

    public function setBirthDate(DateTime $birthDate) {
        $this->birthDate = $birthDate;
    }

    public function __construct() {
        $this->birthDate = null;
    }
}
