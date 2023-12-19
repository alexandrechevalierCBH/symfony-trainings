<?php

namespace App\UseCase\Expense\Create;

use App\Message\Lockable;
use Symfony\Component\Uid\Uuid;

class InputHigh implements Lockable
{
    /**
     * @param array<int, Uuid> $beneficiariesId
     */
    public function __construct(
        public readonly string $description,
        public readonly string $groupSlug,
        public readonly float $amount,
        public readonly Uuid $payerId,
        public readonly array $beneficiariesId
    ) {
    }
}
