<?php

namespace App\DataFixtures;

use App\Entity\Person;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PersonFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // Generating 10 persons
        for ($i = 0; $i < 10; ++$i) {
            $firstname = $faker->firstName();
            $lastname = $faker->lastName();
            $email = $faker->unique()->email();

            $person = new Person($firstname, $lastname, $email);
            $manager->persist($person);
        }

        // writing in database
        $manager->flush();
    }
}
