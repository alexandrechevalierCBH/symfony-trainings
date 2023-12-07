<?php

namespace App\UseCase\Group\Create;

use Symfony\Component\Uid\Uuid;

class Input
{
    /**
     * @param array<int, Uuid> $personsId
     */
    public function __construct(
        public readonly string $label,
        public readonly array $personsId,
        public readonly int $time,
        public readonly ?string $description
    ) {
    }
}
