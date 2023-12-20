<?php

namespace App\Services;

use App\Repository\PersonRepository;
use Exception;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Uid\Uuid;

class Mailer
{
    public function __construct(private MailerInterface $mailer, private PersonRepository $personRepository)
    {
    }

    public function sendMailAfterExpenseCreation(array $recipients): void
    {
        $validEmails = array_filter(
            $recipients,
            static fn ($mail) => null !== $mail
        );

        $email = (new Email())
            ->from('hello@knplabs.com')
            ->to(...$validEmails)
            ->subject('New Expense added to your group!')
            ->text("Hey! New expense added !");

        $this->mailer->send($email);
    }

    public function sendMailAfterExpenseCreationFailure(Uuid $payerId): void
    {
        $payerMail = $this->personRepository->findOneByUuid($payerId)->getEmail();

        if (null === $payerMail) {
            throw new Exception('No mail given');
        }
        $email = (new Email())
            ->from('hello@knplabs.com')
            ->to($payerMail)
            ->subject('Oops')
            ->text("Sans doute à cause du stagiaire");

        $this->mailer->send($email);
    }
}
