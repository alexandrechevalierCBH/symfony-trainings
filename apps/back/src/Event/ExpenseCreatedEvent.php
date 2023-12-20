<?php

namespace App\Event;

use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\EventDispatcher\Event;

class ExpenseCreatedEvent extends Event
{
    public function __construct(
        public readonly array $beneficiaries,
        public readonly Uuid $id
    ) {
    }
}
