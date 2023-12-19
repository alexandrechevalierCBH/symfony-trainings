<?php

namespace App\UseCase\Expense\Create;

use App\Entity\Expense;
use App\Entity\Person;
use App\Repository\GroupRepository;
use App\Repository\PersonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class Handler
{
    public function __construct(private EntityManagerInterface $entityManagerInterface, private PersonRepository $personRepository, private GroupRepository $groupRepository, private LoggerInterface $loggerInterface)
    {
    }

    public function __invoke(Input|InputHigh $input): Output
    {
        // if (rand(0, 10) < 7) {
        //     throw new Exception("padbol");
        // }

        $description = $input->description;
        $group = $this->groupRepository->findOneBySlug($input->groupSlug);
        $amount = (int) ($input->amount * 100);

        if ($amount > 10000) {
            $this->loggerInterface->info("on met un message plus long pour bien le voir");
        }

        $payer = $this->personRepository->findOneByUuid($input->payerId);

        if (!$payer instanceof Person) {
            throw new \Exception('Could not get payer');
        }

        $beneficiaries = $this->personRepository->findPersonsByUuid($input->beneficiariesId);

        if (0 === count($beneficiaries)) {
            throw new \Exception('Could not get the beneficiary list');
        }

        $expense = new Expense($description, $group, $amount, $payer, $beneficiaries);

        $this->entityManagerInterface->persist($expense);

        sleep(5);
        return new Output($expense);
    }
}
