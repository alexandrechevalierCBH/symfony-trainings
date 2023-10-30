<?php

namespace App\DataFixtures;

use App\Entity\Group;
use App\Entity\Person;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class GroupFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $personsRepo = $this->em->getRepository(Person::class);
        $people = $personsRepo->findAll();

        for ($i = 0; $i < 5; ++$i) {
            $label = $faker->word();
            $persons = $faker->randomElements($people, rand(2, 5));
            $description = $faker->sentence();

            $group = new Group(
                $label,
                $persons,
                $description
            );

            $manager->persist($group);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            PersonFixtures::class,
        ];
    }
}
