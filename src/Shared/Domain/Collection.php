<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Shared\Domain;

use ArrayIterator;
use Countable;
use Exception;
use Hiberus\Skeleton\Shared\Domain\Bus\Event\EventSourcedEntity;
use IteratorAggregate;
use Traversable;

/**
 * @template TKey
 * @template TValue
 * @implements IteratorAggregate<TKey, TValue>
 */
abstract class Collection extends EventSourcedEntity implements Countable, IteratorAggregate
{
    /** @param array<int, mixed> $items */
    public function __construct(private array $items = [])
    {
        Assert::arrayOf($this->type(), $items);
    }

    /** @return ArrayIterator<int, TValue> */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items());
    }

    public function count(): int
    {
        return \count($this->items());
    }

    public function add(mixed $item): void
    {
        $this->items[] = $item;
    }

    /** @throws Exception */
    public function remove(mixed $itemToRemove): void
    {
        foreach ($this->getIterator() as $key => $item) {
            if ($item === $itemToRemove) {
                unset($this->items[$key]);
            }
        }
    }

    /** @return array<TValue>> */
    public function items(): array
    {
        return $this->items;
    }

    /** @return array<TValue>> */
    public function last(): mixed
    {
        return end($this->items);
    }

    /** @return TValue */
    public function first(): mixed
    {
        return reset($this->items);
    }

    /**
     * @param Collection<TKey, TValue> $collection
     *
     * @throws \Exception
     */
    public function merge(Collection $collection): void
    {
        foreach ($collection->getIterator() as $iterator) {
            if ($this->type() !== \get_class($iterator) && $this->type() !== get_parent_class($iterator)) {
                throw new Exception(
                    sprintf('Items must be <%s> instead of <%s>', \get_class($iterator), $this->type())
                );
            }
            $this->add($iterator);
        }
    }

    /**
     * @param Collection<TKey, TValue> $collection
     *
     * @throws \Exception
     */
    public function diff(Collection $collection): static
    {
        return new static(array_udiff(
            (array) $this->getIterator(),
            (array) $collection->getIterator(),
            static fn (mixed $a, mixed $b) => $a->id()->value() <=> $b->id()->value()
        ));
    }

    /** @throws \Exception */
    public function each(callable $callback): void
    {
        array_map($callback, (array) $this->getIterator());
    }

    abstract protected function type(): string;
}
