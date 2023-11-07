<?php

namespace App\DataFixtures;

use App\Entity\Expense;
use App\Entity\Group;
use App\Entity\Person;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ClientDataFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $expensables = [
            $jason = new Person('jason', ''),
            $arnold = new Person('arnold', ''),
            $silvestre = new Person('silvestre', ''),
            $bruce = new Person('bruce', ''),
            $jet = new Person('jet', ''),
        ];

        foreach ($expensables as $expensable) {
            $manager->persist($expensable);
        }

        $group = new Group(
            'Les expensables',
            $expensables
        );

        $expenses = [
            new Expense("Tournée générale -- Cas d'une personne qui paye pour tout le monde (lui compris)", $group, 5500, $jason, [$jason, $silvestre, $arnold, $bruce, $jet]),
            new Expense("Cadeau Arnold -- Cas d'une personne qui paye pour un ensemble de bénéficiaires (lui y compris)", $group, 6000, $silvestre, [$jason, $silvestre, $bruce, $jet]),
            new Expense("Remboursement Jet->silvestre -- Cas d'une personne qui rembourse à une autre personne", $group, 1500, $jet, [$silvestre]),
            new Expense("Bruce et son burger -- Cas d'une personne qui prend quelques chose uniquement pour elle", $group, 1600, $bruce, [$bruce]),
            new Expense("Kebab pour tous sauf Bruce et Arnold -- Cas d'une personne qui paye pour un ensemble de bénéficiaires (sans lui)", $group, 4500, $arnold, [$jason, $silvestre, $jet]),
        ];

        foreach ($expenses as $expense) {
            $manager->persist($expense);
        }

        $manager->flush();
    }
}
