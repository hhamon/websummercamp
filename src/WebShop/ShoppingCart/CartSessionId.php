<?php

namespace WebSummerCamp\WebShop\ShoppingCart;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Verraes\ClassFunctions\ClassFunctions;
use WebSummerCamp\EventSourcing\AggregateIdentifier;

final class CartSessionId implements AggregateIdentifier
{
    private $aggregateType;
    private $uuid;

    public function __construct(string $aggregateType, UuidInterface $uuid)
    {
        $this->aggregateType = ClassFunctions::short($aggregateType);
        $this->uuid = $uuid;
    }

    public static function fromString(string $identifier): self
    {
        [$aggregateType, $uuid] = explode('_', $identifier);

        return new self($aggregateType, Uuid::fromString($uuid));
    }

    public function toString(): string
    {
        return sprintf('%s_%s', $this->aggregateType, $this->uuid->toString());
    }
}