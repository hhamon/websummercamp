<?php

namespace WebSummerCamp\EventSourcing;

use Assert\Assertion;

class RecordedEvents implements \Countable
{
    private $events = [];

    public function __construct(array $events = [])
    {
        Assertion::allIsInstanceOf($events, DomainEvent::class);

        $this->events = array_values($events);
    }

    public function add(DomainEvent $event): void
    {
        $this->events[] = $event;
    }

    public function all(): array
    {
        return $this->events;
    }

    public function isEmpty(): bool
    {
        return 0 === $this->count();
    }

    public function count(): int
    {
        return count($this->events);
    }
}