<?php

namespace WebSummerCamp\EventSourcing;

use Assert\Assertion;

class EventStore
{
    private $storage;

    public function __construct(EventStorage $storage)
    {
        $this->storage = $storage;
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

        $this->storage->store($aggregateId, $events);
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
        $stream = [];
        foreach ($this->storage->fetch($identifier) as $event) {
            $stream[] = call_user_func_array([$event['_headers']['eventType'], 'fromPayload'], [$event]);
        }

        return $stream;
    }
}