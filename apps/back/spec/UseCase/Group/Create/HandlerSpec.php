<?php

namespace spec\App\UseCase\Group\Create;

use App\Entity\Group;
use App\Entity\Person;
use App\Repository\PersonRepository;
use App\UseCase\Group\Create\Handler;
use App\UseCase\Group\Create\Input;
use App\UseCase\Group\Create\Output;
use Doctrine\ORM\EntityManagerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Uid\Uuid;
use Webmozart\Assert\Assert;

class HandlerSpec extends ObjectBehavior
{
    public function let(EntityManagerInterface $em, Input $input, PersonRepository $personRepository)
    {
        $this->beConstructedWith($em, $personRepository);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Handler::class);
    }

    public function it_handles_create_group_command(PersonRepository $personRepository, EntityManagerInterface $em, Person $member1, Person $member2, Uuid $memberId1, Uuid $memberId2)
    {
        $personRepository->findPersonsByUuid([$memberId1, $memberId2])->willReturn([$member1, $member2]);

        $time = time();
        $input = new Input(
            'Group label',
            [
                $memberId1,
                $memberId2,
            ],
            $time,
            'La description'
        );

        $em->persist(Argument::that(function ($group) use ($time) {
            Assert::true($group instanceof Group);
            Assert::eq($group->getLabel(), 'Group label');
            Assert::eq($group->getSlug(), "$time-group-label");
            Assert::eq(count($group->getPersons()), 2);
            Assert::true($group->getPersons()[0] instanceof Person);
            Assert::true($group->getPersons()[1] instanceof Person);
            Assert::eq($group->getDescription(), 'La description');

            return true;
        }))->shouldBeCalled();

        $em->flush()->shouldBeCalled();

        $output = $this->__invoke($input);
        $output->shouldHaveType(Output::class);
    }
}
