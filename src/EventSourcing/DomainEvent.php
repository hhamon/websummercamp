<?php

namespace WebSummerCamp\EventSourcing;

interface DomainEvent
{
    public function getAggregateIdentifier(): AggregateIdentifier;

    public function getPayload(): array;

    public function getTimestamp(): \DateTimeImmutable;
}