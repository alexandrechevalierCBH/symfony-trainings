<?php

namespace App\UseCase\Expense\Create;

use App\Entity\Group;
use Symfony\Component\Uid\Uuid;

class Input
{
    /**
     * @param array<int, Uuid> $beneficiariesId
     */
    public function __construct(
        public readonly string $description,
        public readonly Group $group,
        public readonly float $amount,
        public readonly Uuid $payerId,
        public readonly array $beneficiariesId
    ) {
    }
}
