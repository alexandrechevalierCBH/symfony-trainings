<?php

namespace App\Middleware;

use App\Services\Mailer;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\RedeliveryStamp;
use Throwable;

class ExpenseFailedMiddleWare implements MiddlewareInterface
{
    public function __construct(private Mailer $mailer, private LoggerInterface $logger, private int $maxRetries)
    {
    }
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        try {
            return $stack->next()->handle($envelope, $stack);
        } catch (HandlerFailedException $exception) {
            if (null === $this->getNestedException($exception)) {
                throw $exception;
            }

            if (!$this->isLastRetry($envelope, $this->getNestedException($exception))) {
                throw $exception;
            }

            if ($this->isLastRetry($envelope, $exception)) {
                $this->mailer->sendMailAfterExpenseCreationFailure($envelope->getMessage()->payerId);
            }
            return $stack->next()->handle($envelope, $stack);
        }
    }

    private function isLastRetry(Envelope $envelope, Throwable $nestedException): bool
    {
        if ($nestedException instanceof UnrecoverableMessageHandlingException) {
            return true;
        }
        $redeliveryStamp = $envelope->last(RedeliveryStamp::class);

        if (null === $redeliveryStamp) {
            return false;
        }

        return $redeliveryStamp->getRetryCount() === $this->maxRetries;
    }

    public function getNestedException(HandlerFailedException $exception): ?Throwable
    {
        $nestedException = $exception->getNestedExceptions();
        if (count($nestedException) === 1) {
            return $nestedException[0];
        }
        return null;
    }
}
