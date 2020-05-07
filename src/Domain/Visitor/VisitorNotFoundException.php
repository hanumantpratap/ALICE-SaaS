<?php
declare(strict_types=1);

namespace App\Domain\Visitor;

use App\Domain\DomainException\DomainRecordNotFoundException;

class VisitorNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The visitor you requested does not exist.';
}
