<?php
declare(strict_types=1);

namespace App\Domain\Person;

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

/**
 * @Entity
 * @Table(name="person_demographics", schema="public")
 */
class PersonDemographics {
    /**
     * @Id
     * @GeneratedValue
     * @Column(name="pd_id")
     */
    public int $pdId;

    /** @Column(name="gender") */
    public int $gender;

    /** @Column(name="birth_date") */
    public $birthDate;

    /** @Column(name="ethnicity") */
    public ?int $ethnicity;

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

    /** @OneToOne(targetEntity="Person", inversedBy="personDemographics")
     * @JoinColumn(name="person_id", referencedColumnName="person_id")
     */
    protected ?Person $person;

}
