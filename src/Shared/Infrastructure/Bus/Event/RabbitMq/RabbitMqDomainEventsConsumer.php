<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Shared\Infrastructure\Bus\Event\RabbitMq;

use Hiberus\Skeleton\Shared\Domain\Bus\Event\DomainEvent;
use Hiberus\Skeleton\Shared\Domain\Bus\Event\DomainEventSubscriber;
use Hiberus\Skeleton\Shared\Infrastructure\Bus\Event\DomainEventJsonDeserializer;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMqDomainEventsConsumer
{
    private const DEFAULT_MAX_RETRIES = 3;

    public function __construct(
        private readonly RabbitMqConnection $connection,
        private readonly DomainEventJsonDeserializer $deserializer,
        private readonly int $maxRetries = self::DEFAULT_MAX_RETRIES
    ) {
    }

    public function consume(DomainEventSubscriber $subscriber): void
    {
        $eventsSubscribedTo = $subscriber->subscribedTo();
        /** @var DomainEvent $event */
        foreach ($eventsSubscribedTo as $event) {
            $this->connection->declareQueues($subscriber::queue(), $event::eventName());
        }

        $this->connection->consume($subscriber::queue(), $this->consumer($subscriber));
    }

    private function consumer(DomainEventSubscriber $subscriber): callable
    {
        return function (AMQPMessage $envelope) use ($subscriber) {
            try {
                $event = $this->deserializer->deserialize($envelope->getBody());
                $subscriber($event);
            } catch (\Throwable $error) {
                $this->addErrorMessageToBody($envelope, $error->getMessage());
                $this->handleConsumptionError($envelope, $subscriber::queue());

                throw $error;
            }
            $envelope->ack();
        };
    }

    private function addErrorMessageToBody(AMQPMessage $envelope, string $message): void
    {
        $body = json_decode($envelope->getBody(), true);
        $body['error'][] = $message;
        $envelope->setBody((string) json_encode($body));
    }

    private function handleConsumptionError(AMQPMessage $envelope, string $queue): void
    {
        $this->hasBeenRedeliveredTooMuch($envelope)
        ? $this->sendToDeadLetter($envelope, $queue)
        : $this->sendToRetry($envelope, $queue);
        $envelope->ack();
    }

    private function hasBeenRedeliveredTooMuch(AMQPMessage $envelope): bool
    {
        return $this->countRedelivery($envelope) >= $this->maxRetries;
    }

    private function sendToDeadLetter(AMQPMessage $envelope, string $queue): void
    {
        $exchangeName = RabbitMqExchangeNameFormatter::deadLetter($this->connection->getExchangeName());
        $queue = RabbitMqQueueNameFormatter::deadLetter($queue);
        $this->sendMessageTo($exchangeName, $envelope, $queue);
    }

    private function sendToRetry(AMQPMessage $envelope, string $queue): void
    {
        $exchangeName = RabbitMqExchangeNameFormatter::retry($this->connection->getExchangeName());
        $queue = RabbitMqQueueNameFormatter::retry($queue);
        $this->sendMessageTo($exchangeName, $envelope, $queue);
    }

    private function sendMessageTo(string $exchangeName, AMQPMessage $envelope, string $queue): void
    {
        $redeliveryCount = $this->countRedelivery($envelope);

        $this->connection
            ->setExchangeName($exchangeName)
            ->publish(
                $envelope->getBody(),
                $queue,
                $redeliveryCount
            );
    }

    private function countRedelivery(AMQPMessage $envelope): int
    {
        $headers = $envelope->get('application_headers');
        $data = $headers->getNativeData();

        return \array_key_exists('redelivery_count', $data) ? $data['redelivery_count'] + 1 : 1;
    }
}
