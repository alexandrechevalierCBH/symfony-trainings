<?php

namespace spec\App\Entity;

use App\Entity\Expense;
use App\Entity\Group;
use App\Entity\Person;
use Doctrine\Common\Collections\ArrayCollection;
use PhpSpec\ObjectBehavior;

class ExpenseSpec extends ObjectBehavior
{
    public function let(Person $john, Person $jane, Group $group)
    {
        $persons = [$john, $jane];
        $beneficiaries = [$john, $jane];

        $this->beConstructedWith(
            'restaurant',
            $group,
            8.66,
            $john,
            $beneficiaries
        );
    }

    public function is_it_initializable()
    {
        $this->shouldHaveType(Expense::class);
    }

    public function it_returns_the_amount()
    {
        $this->getAmount()->shouldReturn(8.66);
    }

    public function it_returns_john()
    {
        $this->getPayer()->shouldHaveType(Person::class);
    }

    public function it_returns_restaurant()
    {
        $this->getDescription()->shouldReturn('restaurant');
    }

    public function it_returns_the_beneficiaries_collection()
    {
        $this->getBeneficiaries()->shouldHaveType(ArrayCollection::class);
    }

    public function it_returns_a_Group()
    {
        $this->getGroup()->shouldHaveType(Group::class);
    }
}
