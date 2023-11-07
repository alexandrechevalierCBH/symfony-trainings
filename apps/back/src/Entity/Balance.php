<?php

namespace App\Entity;

class Balance
{
    private Person $member;
    private int $amount;

    public function __construct(Person $member, int $amount)
    {
        $this->member = $member;
        $this->amount = $amount;
    }

    public function getMember(): Person
    {
        return $this->member;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }
}
