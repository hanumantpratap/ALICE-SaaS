<?php
declare(strict_types=1);

namespace App\Domain\SexOffender;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;

/**
 * @Entity
 * @Table(name="sex_offenders", schema="visitor_management")
 */
class SexOffender {
    /**
    * @Id
    * @Column(name="offender_id")
    **/
    public ?string $id;

    /** @Column(type="json",options={"jsonb"=true}) */
    public ?array $data;

    public function setId(string $id) {
        $this->id = $id;
    }

    public function setData(array $data) {
        $this->data = $data;
    }

    public function getData() {
        return $this->data;
    }

    public function __construct(string $id = null) {
        $this->id = $id;
        $this->data = [];
    }
}
