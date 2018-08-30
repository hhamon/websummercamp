<?php

namespace WebSummerCamp\EventSourcing;

use Assert\Assertion;

class EventStore
{
    private $directory;

    public function __construct(string $directory)
    {
        Assertion::directory($directory);
        Assertion::writeable($directory);

        $this->directory = $directory;
    }

    public function store(RecordedEvents $recordedEvents): void
    {
        $events = [];
        $aggregateId = null;
        foreach ($recordedEvents->all() as $event) {
            if (null === $aggregateId) {
                $aggregateId = $event->getAggregateIdentifier();
            }

            $events[] = $this->normalize($event);
        }

        $aggregateStorePath = sprintf('%s/%s.json', $this->directory, $aggregateId->toString());
        file_put_contents($aggregateStorePath, json_encode($events));
    }

    private function normalize(DomainEvent $domainEvent): array
    {
        $event['_headers'] = [
            'eventType' => get_class($domainEvent),
            'aggregateId' => $domainEvent->getAggregateIdentifier()->toString(),
            'timestamp' => $domainEvent->getTimestamp()->format(\DATE_ATOM),
        ];

        $event['_payload'] = $domainEvent->getPayload();

        return $event;
    }

    /**
     * @return DomainEvent[]
     */
    public function getStream(AggregateIdentifier $identifier): array
    {
        $aggregateStorePath = sprintf('%s/%s.json', $this->directory, $identifier->toString());
        Assertion::file($aggregateStorePath);

        $events = json_decode(file_get_contents($aggregateStorePath), true);

        $stream = [];
        foreach ($events as $event) {
            $stream[] = call_user_func_array([$event['_headers']['eventType'], 'fromPayload'], [$event]);
        }

        return $stream;
    }
}