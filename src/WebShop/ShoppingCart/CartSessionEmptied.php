<?php

namespace WebSummerCamp\WebShop\ShoppingCart;

use WebSummerCamp\EventSourcing\AggregateIdentifier;
use WebSummerCamp\EventSourcing\DomainEvent;

class CartSessionEmptied implements DomainEvent
{
    private $aggregateId;
    private $timestamp;

    public function __construct(AggregateIdentifier $aggregateIdentifier, \DateTimeImmutable $timestamp = null)
    {
        $this->aggregateId = $aggregateIdentifier->toString();
        $timestamp = $timestamp ?: new \DateTimeImmutable('now', new \DateTimeZone('UTC'));
        $this->timestamp = (int) $timestamp->format('U');
    }

    public static function fromPayload(array $payload): self
    {
        return new self(
            CartSessionId::fromString($payload['_headers']['aggregateId']),
            \DateTimeImmutable::createFromFormat(\DATE_ATOM, $payload['_headers']['timestamp'])
        );
    }

    public function getAggregateIdentifier(): AggregateIdentifier
    {
        return CartSessionId::fromString($this->aggregateId);
    }

    public function getPayload(): array
    {
        return [];
    }

    public function getTimestamp(): \DateTimeImmutable
    {
        return \DateTimeImmutable::createFromFormat('U', $this->timestamp);
    }
}