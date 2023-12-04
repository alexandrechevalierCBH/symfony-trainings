<?php

namespace App\UseCase\Expense\Create;

use App\Entity\Expense;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class Handler
{
    public function __construct(private EntityManagerInterface $entityManagerInterface)
    {
    }

    public function __invoke(Input $input): Output
    {
        $description = $input->getDescription();
        $group = $input->getGroup();

        $amount = (int) ($input->getAmount() * 100);
        $payer = $input->getPayer();
        $beneficiaries = $input->getBeneficiaries();

        $expense = new Expense($description, $group, $amount, $payer, $beneficiaries);

        $this->entityManagerInterface->persist($expense);
        $this->entityManagerInterface->flush();

        return new Output($expense);
    }
}
