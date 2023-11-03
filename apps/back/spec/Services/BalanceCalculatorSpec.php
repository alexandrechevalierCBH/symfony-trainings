<?php

namespace spec\App\Services;

use App\Entity\Expense;
use App\Entity\Group;
use App\Entity\Person;
use App\Services\BalanceCalculator;
use PhpSpec\ObjectBehavior;
use Webmozart\Assert\Assert;

class BalanceCalculatorSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(BalanceCalculator::class);
    }

    public function it_returns_balances_for_each_member(
        Group $group,
        Person $john,
        Person $jane,
        Person $jill,
        Expense $expense1,
        Expense $expense2,
        Expense $expense3
    ) {
        $john = new Person('john', 'doe');
        $jane = new Person('jane', 'doe');
        $jill = new Person('jill', 'doe');

        $group = new Group(
            'group',
            [
                $john,
                $jane,
                $jill,
            ]
        );

        $expense1 = new Expense('exp1', $group, floatval(90), $jane, [$john, $jane, $jill]);
        $expense2 = new Expense('exp2', $group, floatval(47), $jill, [$john, $jane]);
        $expense3 = new Expense('exp3', $group, floatval(10), $jill, [$john, $jill, $jane]);

        $group->addExpenses(
            [
                $expense1,
                $expense2,
                $expense3,
            ]
        );

        $balances = $this->getBalances($group);

        $balances[0]->getMember()->shouldBe($john);
        $balances[1]->getMember()->shouldBe($jane);
        $balances[2]->getMember()->shouldBe($jill);

        Assert::same(
            intval(
                array_sum(
                    array_map(
                        fn ($amount) => $amount->getAmount(),
                        $balances->getWrappedObject()
                    )
                )
            ),
            0
        );
    }
}
