<?php

namespace App\DataFixtures;

use App\Entity\Expense;
use App\Entity\Group;
use App\Entity\Person;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ExpenseFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 10; ++$i) {
            $groupRepo = $this->em->getRepository(Group::class);
            $groups = $groupRepo->findAll();

            /** @var Group $group */
            $group = $faker->randomElement($groups);
            $beneficiaries = $group->getPersons();

            /** @var Person $payer */
            $payer = $faker->randomElement($beneficiaries);

            $expense = new Expense(
                $faker->sentence(),
                $group,
                $faker->randomFloat(2, 8, 128),
                $payer,
                $beneficiaries->toArray(),
            );
            $manager->persist($expense);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            PersonFixtures::class,
            GroupFixtures::class,
        ];
    }
}
