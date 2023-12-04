<?php

namespace App\UseCase\Expense\Create;

use App\Entity\Group;
use App\Entity\Person;

class Input
{
    private string $description;

    private Group $group;

    private float $amount;

    private Person $payer;

    /**
     * @var array<int, Person>
     */
    private array $beneficiaries;

    /**
     * @param array<int, Person> $beneficiaries
     */
    public function __construct(string $description, Group $group, float $amount, Person $payer, array $beneficiaries)
    {
        $this->description = $description;
        $this->group = $group;
        $this->amount = $amount;
        $this->payer = $payer;
        $this->beneficiaries = $beneficiaries;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getGroup(): Group
    {
        return $this->group;
    }

    public function getAmount(): float
    {
        return (float) $this->amount;
    }

    public function getPayer(): Person
    {
        return $this->payer;
    }

    /**
     * @return array<int, Person>
     */
    public function getBeneficiaries(): array
    {
        return $this->beneficiaries;
    }
}
