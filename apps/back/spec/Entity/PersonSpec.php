<?php

namespace spec\App\Entity;

use App\Entity\Person;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Uid\Uuid;

class PersonSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(' firstname', 'lastname ');
        $this->getId()->shouldHaveType(Uuid::class);
    }

    public function is_it_initializable()
    {
        $this->shouldHaveType(Person::class);
    }

    public function it_concats_the_firstname_and_the_lastname()
    {
        $this->getFullname()->shouldReturn('firstname lastname');
    }
}
