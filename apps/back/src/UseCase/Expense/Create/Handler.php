<?php

namespace App\UseCase\Expense\Create;

use App\Entity\Expense;
use App\Entity\Person;
use App\Event\ExpenseCreatedEvent;
use App\Repository\GroupRepository;
use App\Repository\PersonRepository;
use App\Services\Mailer;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DispatchAfterCurrentBusStamp;

#[AsMessageHandler]
class Handler
{
    public function __construct(private EntityManagerInterface $entityManagerInterface, private PersonRepository $personRepository, private GroupRepository $groupRepository, private LoggerInterface $loggerInterface, private MessageBusInterface $eventBus, private Mailer $mailer)
    {
    }

    public function __invoke(Input|InputHigh $input): Output
    {
        if (rand(0, 10) < 7) {
            throw new Exception("Random Error");
        }

        $description = $input->description;
        $group = $this->groupRepository->findOneBySlug($input->groupSlug);
        $amount = (int) ($input->amount * 100);

        if ($amount > 10000) {
            $this->loggerInterface->info("This is a high amount");
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

        $id = $expense->getId();

        $mailsAdresses = array_map(
            static fn ($recipient) => $recipient->getEmail(),
            $beneficiaries
        );

        $envelope = new Envelope(new ExpenseCreatedEvent($mailsAdresses, $id));
        $this->eventBus->dispatch($envelope->with(new DispatchAfterCurrentBusStamp()));

        return new Output($expense);
    }
}
