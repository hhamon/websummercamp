<?php

namespace WebSummerCamp\EventSourcing;

use Assert\Assertion;

class FilesystemEventStorage implements EventStorage
{
    private $directory;

    public function __construct(string $directory)
    {
        Assertion::directory($directory);
        Assertion::writeable($directory);

        $this->directory = $directory;
    }

    public function store(AggregateIdentifier $identifier, array $events): void
    {
        $aggregateStorePath = sprintf('%s/%s.json', $this->directory, $identifier->toString());
        file_put_contents($aggregateStorePath, json_encode($events));
    }

    public function fetch(AggregateIdentifier $identifier): array
    {
        $aggregateStorePath = sprintf('%s/%s.json', $this->directory, $identifier->toString());
        Assertion::file($aggregateStorePath);

        return json_decode(file_get_contents($aggregateStorePath), true);
    }
}