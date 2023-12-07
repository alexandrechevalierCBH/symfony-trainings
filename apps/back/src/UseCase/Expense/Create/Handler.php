<?php

namespace App\UseCase\Expense\Create;

use App\Entity\Expense;
use App\Entity\Person;
use App\Repository\PersonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class Handler
{
    public function __construct(private EntityManagerInterface $entityManagerInterface, private PersonRepository $personRepository)
    {
    }

    public function __invoke(Input $input): Output
    {
        $description = $input->description;
        $group = $input->group;
        $amount = (int) ($input->amount * 100);

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
        $this->entityManagerInterface->flush();

        return new Output($expense);
    }
}
