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
            100,
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
        $this->getAmount()->shouldReturn(100);
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

    public function it_returns_the_unitary_shared()
    {
        $this->getUnitaryShared()->shouldReturn(50);
    }

    public function it_calculates_user_share(Person $john, Person $jane, Group $group)
    {
        $this->getUserShare($john)->shouldBe(50);
        $this->getUserShare($jane)->shouldBe(-50);
    }

    public function it_throws_exception_if_no_beneficiaries(Person $payer, Group $group)
    {
        $this->beConstructedWith('Expense description', $group, 100, $payer, []);

        $this->shouldThrow(\InvalidArgumentException::class)->duringInstantiation();
    }
}
