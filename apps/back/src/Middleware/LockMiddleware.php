<?php

namespace App\Middleware;

use App\Message\Lockable;
use App\Stamp\LockStamp;
use RuntimeException;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

class LockMiddleware implements MiddlewareInterface
{
    public function __construct(
        private LockFactory $lockFactory
    ) {
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $message = $envelope->getMessage();

        if (!$message instanceof Lockable) {
            return $stack->next()->handle($envelope, $stack);
        }

        if ([] === $envelope->all(LockStamp::class)) {
            $envelope = $envelope->with(new LockStamp(
                $message->groupSlug . time()
            ));

            return $stack->next()->handle($envelope, $stack);
        }

        /** @var LockStamp $lockStamp */
        $lockStamp = $envelope->last(LockStamp::class);

        $lock = $this->lockFactory->createLock((string) $lockStamp->lockName);

        if ($lock->acquire()) {
            $return = $stack->next()->handle($envelope, $stack);

            $lock->release();

            return $return;
        }

        throw new RuntimeException("Unable to acquire the lock for {$message->groupSlug}");
    }
}