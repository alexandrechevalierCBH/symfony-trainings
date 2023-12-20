<?php

namespace App\Event;

use App\Stamp\LockStamp;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Messenger\Event\WorkerMessageFailedEvent;
use Symfony\Component\Messenger\Event\WorkerMessageHandledEvent;
use Symfony\Component\Messenger\Event\WorkerMessageReceivedEvent;
use Symfony\Component\Messenger\Event\WorkerRunningEvent;
use Symfony\Component\Messenger\Event\WorkerStartedEvent;
use Symfony\Component\Messenger\Event\WorkerStoppedEvent;

#[AsEventListener(event: WorkerStartedEvent::class, method: 'onWorkerStartedEvent')]
#[AsEventListener(event: WorkerRunningEvent::class, method: 'onWorkerRunningEvent')]
#[AsEventListener(event: WorkerStoppedEvent::class, method: 'onWorkerStoppedEvent')]
#[AsEventListener(event: WorkerMessageHandledEvent::class, method: 'onWorkerMessageHandledEvent')]
#[AsEventListener(event: WorkerMessageReceivedEvent::class, method: 'onWorkerMessageReceivedEvent')]
#[AsEventListener(event: WorkerMessageFailedEvent::class, method: 'onWorkerMessageFailedEvent')]
class WorkerListener
{
    public function __construct(
        private LoggerInterface $loggerInterface
    ) {
    }

    public function onWorkerStartedEvent(WorkerStartedEvent $event)
    {
        $transport = $event->getWorker()->getMetadata()->getTransportNames();
        $this->loggerInterface->log(LogLevel::NOTICE, 'Worker started:' . implode(" / ", $transport));
    }

    public function onWorkerRunningEvent()
    {
        $this->loggerInterface->log(LogLevel::NOTICE, 'Worker is running toute la journée');
    }

    public function onWorkerStoppedEvent()
    {
        $this->loggerInterface->log(LogLevel::EMERGENCY, 'Worker has stopped');
    }

    public function onWorkerMessageHandledEvent(WorkerMessageHandledEvent $event)
    {
        $message = $event->getEnvelope()->getMessage();
        $messageClass = get_class($message);
        $this->loggerInterface->log(LogLevel::INFO, 'Worker has handled' . $messageClass);
    }

    public function onWorkerMessageReceivedEvent()
    {
        $this->loggerInterface->log(LogLevel::CRITICAL, 'WorkerMessageReceivedEvent');
    }

    public function onWorkerMessageFailedEvent(WorkerMessageFailedEvent $event)
    {
        $retry = $event->willRetry();
        $lock = $event->getEnvelope()->all(LockStamp::class)[0]->lockName;
        // dump($lock, $retry);
        // dump($event);
        $this->loggerInterface->log(LogLevel::CRITICAL, 'WorkerMessageFailedEvent');
    }
}
