<?php

namespace App\UseCase\Group\Show;

use App\Entity\Expense;
use App\Entity\Group;

class Output
{
    private Group $group;
    /**
     * @var array<int, Expense>
     */
    private array $expenses;
    private ?string $flash;

    /**
     * @param array<int, Expense> $expenses
     */
    public function __construct(Group $group, array $expenses, ?string $flash)
    {
        $this->group = $group;
        $this->expenses = $expenses;
        $this->flash = $flash;
    }

    public function getGroup(): Group
    {
        return $this->group;
    }

    /**
     * @return array<int, Expense>
     */
    public function getExpenses(): array
    {
        return $this->expenses;
    }

    public function getFlash(): string|null
    {
        return $this->flash;
    }
}
