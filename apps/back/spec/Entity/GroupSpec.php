<?php

namespace spec\App\Entity;

use App\Entity\Group;
use App\Entity\Person;
use Doctrine\Common\Collections\Collection;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Uid\Uuid;

class GroupSpec extends ObjectBehavior
{
    public function let()
    {
        $john = new Person('john', 'doe', 'mail@mail.com');

        $this->beConstructedWith('group label', 'group description');
        $this->addPerson($john);
        $this->getPersons()->shouldHaveType(Collection::class);
        $this->getId()->shouldHaveType(Uuid::class);
    }

    public function is_it_initializable()
    {
        $this->shouldHaveType(Group::class);
    }

    public function it_returns_the_name_of_the_first_person()
    {
        $this->getPersons()[0]->getLastname()->shouldReturn('doe');
    }
}
