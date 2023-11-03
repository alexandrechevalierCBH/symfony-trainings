<?php

namespace App\Entity;

class Balance
{
    private Person $member;
    private float $amount;

    public function __construct(Person $member, float $amount)
    {
        $this->member = $member;
        $this->amount = $amount;
    }

    public function getMember(): Person
    {
        return $this->member;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }
}
