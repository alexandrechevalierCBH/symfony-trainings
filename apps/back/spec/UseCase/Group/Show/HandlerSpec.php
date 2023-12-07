<?php

namespace spec\App\UseCase\Group\Show;

use App\Entity\Group;
use App\Repository\ExpenseRepository;
use App\Repository\GroupRepository;
use App\UseCase\Group\Show\Handler;
use App\UseCase\Group\Show\Input;
use App\UseCase\Group\Show\Output;
use PhpSpec\ObjectBehavior;

class HandlerSpec extends ObjectBehavior
{
    public function let(GroupRepository $groupRepository, ExpenseRepository $expenseRepository)
    {
        $this->beConstructedWith($groupRepository, $expenseRepository);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Handler::class);
    }

    public function it_handles_the_display_of_the_group_details(Group $group, GroupRepository $groupRepository, ExpenseRepository $expenseRepository)
    {
        $input = new Input(
            'the-group-slug',
            1,
            5,
        );

        $groupRepository->findOneBySlug('the-group-slug')->willReturn($group);
        $expenseRepository->findAndPaginateExpenses($group, 1, 5)->willReturn([]);

        $output = $this->__invoke($input);
        $output->shouldHaveType(Output::class);
    }
}
