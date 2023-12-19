<?php

namespace App\Command;

use App\Bus\CommandBus;
use App\Entity\Expense;
use App\Entity\Group;
use App\Repository\GroupRepository;
use App\UseCase\Expense\Create\Input;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class ExpenseCommand extends Command
{
    protected static $defaultName = "app:expense:create";

    public function __construct(
        private MessageBusInterface $bus,
        private GroupRepository $groupRepo,
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Dispatch expense messages')->setHelp('Oui');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $faker = Factory::create();

        for ($i = 0; $i < 100; ++$i) {
            $groups = $this->groupRepo->findAll();

            /** @var Group $group */
            $group = $faker->randomElement($groups);
            $beneficiaries = $group->getPersons();
            $beneficiariesId = array_map(
                static fn ($person) => $person->getId(),
                $beneficiaries->toArray()
            );

            /** @var Person $payer */
            $payer = $faker->randomElement($beneficiaries);

            $input = new Input(
                $faker->sentence(),
                $group->getSlug(),
                random_int(8, 99),
                $payer->getId(),
                $beneficiariesId,
            );

            $this->bus->dispatch($input);
        }
        return Command::SUCCESS;
    }
}
