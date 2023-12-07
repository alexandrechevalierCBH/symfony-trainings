<?php

namespace App\UseCase\Group\Show;

class Input
{
    public function __construct(
        public readonly string $slug,
        public readonly int $page,
        public readonly int $step)
    {
    }
}
