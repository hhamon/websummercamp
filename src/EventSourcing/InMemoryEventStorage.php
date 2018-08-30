<?php

namespace WebSummerCamp\EventSourcing;

class InMemoryEventStorage implements EventStorage
{
    private $events = [];

    public function store(AggregateIdentifier $identifier, array $events): void
    {
        $this->events[$identifier->toString()] = array_merge(
            $this->fetch($identifier),
            $events
        );
    }

    public function fetch(AggregateIdentifier $identifier): array
    {
        return $this->events[$identifier->toString()] ?? [];
    }
}