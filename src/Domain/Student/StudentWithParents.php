<?php
declare(strict_types=1);

namespace App\Domain\Student;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;

/**
 * @Entity
 * @Table(name="students", schema="respond")
 */
class StudentWithParents extends Student {
  public function jsonSerialize() {
      $obj = parent::jsonSerialize();
      $obj['parentAssociationArray'] = $this->getParentAssociations();
      return $obj;
  }
}