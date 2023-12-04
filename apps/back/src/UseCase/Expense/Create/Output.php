<?php

namespace App\UseCase\Expense\Create;

use App\Entity\Expense;

class Output
{
    private Expense $expense;

    public function __construct(Expense $expense)
    {
        $this->expense = $expense;
    }

    public function getExpense(): Expense
    {
        return $this->expense;
    }
}
