<?php

// spec/App/UseCase/Expense/Create/HandlerSpec.php

namespace spec\App\UseCase\Expense\Create;

use App\Entity\Expense;
use App\Entity\Group;
use App\Entity\Person;
use App\UseCase\Expense\Create\Handler;
use App\UseCase\Expense\Create\Input;
use App\UseCase\Expense\Create\Output;
use Doctrine\ORM\EntityManagerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Webmozart\Assert\Assert;

class HandlerSpec extends ObjectBehavior
{
    function let(EntityManagerInterface $em, Input $input)
    {
        $this->beConstructedWith($em);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Handler::class);
    }

    function it_handles_create_expense_command(EntityManagerInterface $em, Group $group, Person $payer, Person $beneficiary1, Person $beneficiary2)
    {
        $input = new Input('Expense Description', $group->getWrappedObject(), 10.5, $payer->getWrappedObject(), [$beneficiary1->getWrappedObject(), $beneficiary2->getWrappedObject()]);

        $em->persist(Argument::that(function ($expense) use ($input) {
            Assert::true($expense instanceof Expense);
            Assert::eq($expense->getDescription(), 'Expense Description');
            Assert::true($expense->getGroup() instanceof Group);
            Assert::eq($expense->getAmount(), 1050);
            Assert::true($expense->getPayer() instanceof Person);
            Assert::eq(count($expense->getBeneficiaries()), 2);
            Assert::true($expense->getBeneficiaries()[0] instanceof Person);
            Assert::true($expense->getBeneficiaries()[1] instanceof Person);
            return true;
        }))->shouldBeCalled();

        $em->flush()->shouldBeCalled();

        $output = $this->__invoke($input);
        $output->shouldHaveType(Output::class);
    }
}
