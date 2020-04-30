<?php
namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityRepository;

/**
 * Visits
 *
 * @ORM\Table(name="visitor_management.visits", indexes={@ORM\Index(name="IDX_300552BE3147C936", columns={"people_id"}), @ORM\Index(name="IDX_300552BE59BB1592", columns={"reason_id"}), @ORM\Index(name="IDX_300552BE4DFE3A85", columns={"identification_id"})})
 * @ORM\Entity(repositoryClass="App\Entities\Visits")
 */

class Visits extends EntityRepository
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="visitor_management.visits_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_created", type="datetimetz", nullable=true)
     */
    private $dateCreated;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="check_in", type="datetimetz", nullable=true)
     */
    private $checkIn;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="check_out", type="datetimetz", nullable=true)
     */
    private $checkOut;

    /**
     * @var int|null
     *
     * @ORM\Column(name="user_id", type="integer", nullable=true)
     */
    private $userId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="notes", type="text", nullable=true)
     */
    private $notes;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="estimated_check_in", type="datetimetz", nullable=true)
     */
    private $estimatedCheckIn;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="estimated_check_out", type="datetimetz", nullable=true)
     */
    private $estimatedCheckOut;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set dateCreated.
     *
     * @param \DateTime|null $dateCreated
     *
     * @return Visits
     */
    public function setDateCreated($dateCreated = null)
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * Get dateCreated.
     *
     * @return \DateTime|null
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Set checkIn.
     *
     * @param \DateTime|null $checkIn
     *
     * @return Visits
     */
    public function setCheckIn($checkIn = null)
    {
        $this->checkIn = $checkIn;

        return $this;
    }

    /**
     * Get checkIn.
     *
     * @return \DateTime|null
     */
    public function getCheckIn()
    {
        return $this->checkIn;
    }

    /**
     * Set checkOut.
     *
     * @param \DateTime|null $checkOut
     *
     * @return Visits
     */
    public function setCheckOut($checkOut = null)
    {
        $this->checkOut = $checkOut;

        return $this;
    }

    /**
     * Get checkOut.
     *
     * @return \DateTime|null
     */
    public function getCheckOut()
    {
        return $this->checkOut;
    }

    /**
     * Set userId.
     *
     * @param int|null $userId
     *
     * @return Visits
     */
    public function setUserId($userId = null)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId.
     *
     * @return int|null
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set notes.
     *
     * @param string|null $notes
     *
     * @return Visits
     */
    public function setNotes($notes = null)
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * Get notes.
     *
     * @return string|null
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Set estimatedCheckIn.
     *
     * @param \DateTime|null $estimatedCheckIn
     *
     * @return Visits
     */
    public function setEstimatedCheckIn($estimatedCheckIn = null)
    {
        $this->estimatedCheckIn = $estimatedCheckIn;

        return $this;
    }

    /**
     * Get estimatedCheckIn.
     *
     * @return \DateTime|null
     */
    public function getEstimatedCheckIn()
    {
        return $this->estimatedCheckIn;
    }

    /**
     * Set estimatedCheckOut.
     *
     * @param \DateTime|null $estimatedCheckOut
     *
     * @return Visits
     */
    public function setEstimatedCheckOut($estimatedCheckOut = null)
    {
        $this->estimatedCheckOut = $estimatedCheckOut;

        return $this;
    }

    /**
     * Get estimatedCheckOut.
     *
     * @return \DateTime|null
     */
    public function getEstimatedCheckOut()
    {
        return $this->estimatedCheckOut;
    }
}
