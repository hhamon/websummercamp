<?php

namespace WebSummerCamp\WebShop\ShoppingCart;

use Assert\Assertion;
use Money\Currency;
use Money\Money;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use WebSummerCamp\EventSourcing\AggregateIdentifier;
use WebSummerCamp\EventSourcing\DomainEvent;

class ProductAdded implements DomainEvent
{
    private $aggregateId;
    private $timestamp;
    private $sku;
    private $unitPrice;
    private $currency;
    private $quantity;

    public function __construct(
        AggregateIdentifier $aggregateIdentifier,
        UuidInterface $sku,
        Money $unitPrice,
        int $quantity,
        \DateTimeImmutable $timestamp = null
    ) {
        Assertion::greaterOrEqualThan($quantity, 1);

        $this->aggregateId = $aggregateIdentifier->toString();
        $timestamp = $timestamp ?: new \DateTimeImmutable('now', new \DateTimeZone('UTC'));
        $this->timestamp = (int) $timestamp->format('U');
        $this->sku = $sku->toString();
        $this->unitPrice = $unitPrice->getAmount();
        $this->currency = (string) $unitPrice->getCurrency();
        $this->quantity = $quantity;
    }

    public function getAggregateIdentifier(): AggregateIdentifier
    {
        return CartSessionId::fromString($this->aggregateId);
    }

    public function getPayload(): array
    {
        return [
            'sku' => $this->sku,
            'unitPrice' => $this->unitPrice,
            'currency' => $this->currency,
            'quantity' => $this->quantity,
        ];
    }

    public function getTimestamp(): \DateTimeImmutable
    {
        return \DateTimeImmutable::createFromFormat('U', $this->timestamp);
    }

    public function getSku(): UuidInterface
    {
        return Uuid::fromString($this->sku);
    }

    public function getUnitPrice(): Money
    {
        return new Money($this->unitPrice, new Currency($this->currency));
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}