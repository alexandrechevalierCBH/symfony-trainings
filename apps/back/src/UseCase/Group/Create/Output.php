<?php

namespace App\UseCase\Group\Create;

use App\Entity\Group;

class Output
{
    private Group $group;

    public function __construct(Group $group)
    {
        $this->group = $group;
    }

    public function getGroup(): Group
    {
        return $this->group;
    }
}
