<?php
declare(strict_types=1);

namespace App\Domain\SexOffender;

use DateTime;
use App\Domain\Person\Person;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Id;

/**
 * @Entity
 * @Table(name="sex_offenders_negative_matches", schema="visitor_management")
 */
class SexOffenderNonMatch {
    /** @Column(name="person_id") */
    public int $personId;

    /** 
   * @Id
   * @ManyToOne(targetEntity="\App\Domain\Person\Person", inversedBy="sexOffenderNonMatches")
   * @JoinColumn(name="person_id", referencedColumnName="person_id")
   */
    protected Person $person;

    /** @Column(name="sex_offender_id") */
    public string $sexOffenderId;

    /** 
   * @Id
   * @ManyToOne(targetEntity="SexOffender", fetch="EAGER")
   * @JoinColumn(name="sex_offender_id", referencedColumnName="offender_id")
   */
    protected SexOffender $sexOffender;


    /** @Column(name="date_entered", type="datetime") */
    public DateTime $dateEntered;

    /** @Column(name="entered_by")*/
    public ?int $enteredBy;

    public function setPerson(Person $person) {
        $this->person = $person;
    }

    public function setSexOffender(SexOffender $sexOffender) {
        $this->sexOffender = $sexOffender;
    }
    
    public function getSexOffenderId() {
        return $this->sexOffenderId;
    }

    public function setEnteredBy(int $enteredBy) {
        $this->enteredBy = $enteredBy;
    }

    public function __construct() {
        $this->dateEntered = new DateTime();
    }
}
