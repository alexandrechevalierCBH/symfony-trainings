<?php

namespace spec\App\Entity;

use App\Entity\Balance;
use App\Entity\Expense;
use App\Entity\Group;
use App\Entity\Person;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Uid\Uuid;

class GroupSpec extends ObjectBehavior
{
    public function let(Person $john, Person $jane, Expense $expense1, Expense $expense2, Balance $balance1, Balance $balance2)
    {
        $expense1->getAmount()->willReturn(47);
        $expense2->getAmount()->willReturn(100);

        $this->beConstructedWith('group label', [$john, $jane], 'group description');
        $this->addExpenses([$expense1, $expense2]);

        $this->getPersons()->shouldHaveType(Collection::class);
        $this->getId()->shouldHaveType(Uuid::class);
    }

    public function is_it_initializable()
    {
        $this->shouldHaveType(Group::class);
    }

    public function it_returns_expenses_collection()
    {
        $this->getExpenses()->shouldHaveType(ArrayCollection::class);
    }

    public function it_returns_persons_collection()
    {
        $this->getPersons()->shouldHaveType(ArrayCollection::class);
    }

    public function it_returns_group_label()
    {
        $this->getLabel()->shouldReturn('group label');
    }

    public function it_returns_group_description()
    {
        $this->getDescription()->shouldReturn('group description');
    }

    public function it_returns_the_total_amount_of_group_expenses()
    {
        $this->getTotalExpenses()->shouldReturn(147);
    }
}
