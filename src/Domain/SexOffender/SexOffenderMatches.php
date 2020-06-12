<?php
declare(strict_types=1);

namespace App\Domain\SexOffenderMatches;

use DateTime;
use App\Domain\User\User;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;

/**
 * @Entity
 * @Table(name="visitor_sex_offender_matches", schema="visitor_management")
 */
class SexOffenderMatches {
    /**
    * @Id
    * @GeneratedValue
    * @Column(name="id")
    */
    public ?int $id;

    /** @Column */
    public int $personId;

    /** @Column(name="sex_offender_id") */
    private ?string $sexOffenderId;

    /** @Column(name="is_match", type="boolean")*/
    public ?bool $isMatch;

    /** @Column(name="date_entered", type="datetime") */
    public ?DateTime $dateEntered;

    /** @Column(name="entered_by")*/
    public ?int $enteredBy;

    /**
     * @ManyToOne(targetEntity="Person", fetch="EAGER")
     * @JoinColumn(name="person_id", referencedColumnName="person_id")
     */
    protected ?Person $person;

    /**
     * @ManyToMany(targetEntity="SexOffender", fetch="EAGER")
     * @JoinColumn(name="sex_offender_id", referencedColumnName="offender_id")
     */
    protected ?SexOffender $sexOffender;

    public function setIsMatch($isMatch) {
        $this->isMatch = $isMatch;
    }

    public function __construct() {
        $this->personId = null;
        $this->sexOffenderId = null;
        $this->isMatch = false;
        $this->dateEntered = new DateTime();
        $this->enteredBy = null;
    }
}
