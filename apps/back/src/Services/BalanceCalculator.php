<?php

namespace App\Services;

use App\Entity\Balance;
use App\Entity\Group;

class BalanceCalculator
{
    /**
     * @param Group $group
     *
     * @return array<Balance>
     */
    public static function getBalances($group): array
    {
        $balances = [];
        $positives = [];
        $negatives = [];

        foreach ($group->getPersons() as $member) {
            $balance = 0;
            foreach ($group->getExpenses() as $expense) {
                $balance += $expense->getUserShare($member);
            }
            $memberBalance = new Balance($member, $balance);
            $balances[] = $memberBalance;
        }

        foreach ($balances as $balance) {
            if ($balance->getAmount() > 0) {
                $positives[] = $balance;
            } else {
                $negatives[] = $balance;
            }
        }

        $pos = array_map(fn ($amount) => $amount->getAmount(), $positives);
        $neg = array_map(fn ($amount) => $amount->getAmount(), $negatives);

        $centsRemaining = array_sum($pos) + array_sum($neg);

        if (0 !== $centsRemaining) {
            $negatives[array_rand($negatives)]->setAmount($negatives[0]->getAmount() - $centsRemaining);
        }

        return $balances;
    }
}
