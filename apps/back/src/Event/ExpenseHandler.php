<?php

namespace App\Event;

use App\Repository\ExpenseRepository;
use App\Services\Mailer;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ExpenseHandler
{
    public function __construct(
        private Mailer $mailer,
        private LoggerInterface $loggerInterface,
        private ExpenseRepository $expenseRepository
    ) {
    }

    public function __invoke(ExpenseCreatedEvent $event)
    {
        if ($this->expenseRepository->findByUuid($event->id)) {
            $this->mailer->sendMailAfterExpenseCreation($event->beneficiaries);
        }
    }
}
