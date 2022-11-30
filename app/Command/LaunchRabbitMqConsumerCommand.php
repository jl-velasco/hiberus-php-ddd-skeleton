<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\App\Command;

use Hiberus\Skeleton\Shared\Domain\Bus\Event\DomainEventSubscriber;
use Hiberus\Skeleton\Shared\Infrastructure\Bus\Event\RabbitMq\RabbitMqDomainEventsConsumer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LaunchRabbitMqConsumerCommand extends Command
{
    protected static $defaultName = 'rabbitmq:consumer';

    /** @var DomainEventSubscriber[] */
    private array $consumerList;

    /**
     * @param iterable<DomainEventSubscriber> $consumerList
     */
    public function __construct(
        private readonly RabbitMqDomainEventsConsumer $consumer,
        iterable $consumerList,
    ) {
        parent::__construct();
        $this->consumerList = $consumerList instanceof \Traversable ?
            iterator_to_array($consumerList) :
            (array) $consumerList;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Launch consumer for RabbitMQ')
            ->addArgument('consumer', InputArgument::REQUIRED, 'Consumer class name');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $consumerClassName = (string) $input->getArgument('consumer');
        $consumer = $this->getConsumerFromConsumerClassName($consumerClassName);

        if (null === $consumer) {
            $output->writeln("Consumer <{$consumerClassName}>not found");
            $output->writeln('Consumers available:');
            array_map(static function (string $consumerName) use ($output) {
                $output->writeln("- {$consumerName}");
            }, $this->getAvailableConsumersName());

            return self::FAILURE;
        }

        if (\is_callable($consumer)) {
            $this->consumer->consume($consumer);
        }

        return self::SUCCESS;
    }

    /** @return array<int, string> */
    private function getAvailableConsumersName(): array
    {
        return array_map(function (DomainEventSubscriber $consumer) {
            $className = $consumer::class;
            $namespace = explode('\\', $className);

            return end($namespace);
        }, $this->consumerList);
    }

    private function getConsumerFromConsumerClassName(string $consumerClassName): ?DomainEventSubscriber
    {
        foreach ($this->consumerList as  $value) {
            if (strpos($value::class, $consumerClassName)) {
                return $value;
            }
        }

        return null;
    }
}
