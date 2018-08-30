<?php

namespace WebSummerCamp\WebShop\ShoppingCart;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use WebSummerCamp\EventSourcing\AggregateIdentifier;
use WebSummerCamp\EventSourcing\AggregateRoot;
use WebSummerCamp\EventSourcing\DomainEvent;
use WebSummerCamp\EventSourcing\RecordedEvents;
use WebSummerCamp\WebShop\Product;

final class CartSession implements AggregateRoot
{
    private $identifier;
    private $recordedEvents;

    public $initiated = false;
    public $content = [];

    private function __construct(CartSessionId $identifier)
    {
        $this->identifier = $identifier;
        $this->recordedEvents = new RecordedEvents();
    }

    private function recordThat(DomainEvent $domainEvent): void
    {
        $this->recordedEvents->add($domainEvent);
        $this->apply($domainEvent);
    }

    /**
     * @param DomainEvent[] $stream
     */
    public static function fromEventStream(CartSessionId $identifier, array $stream): self
    {
        $session = new self($identifier);
        foreach ($stream as $event) {
            $session->apply($event);
        }

        return $session;
    }

    public function apply(DomainEvent $event): void
    {
        switch (true) {
            case $event instanceof CartSessionInitiated:
                $this->initiated = true;
                break;
            case $event instanceof ProductAdded:
                $this->content[$event->getSku()->toString()] = [
                    'quantity' => $event->getQuantity(),
                    'price' => $event->getUnitPrice(),
                ];
                break;
        }
    }

    public static function initiate(CartSessionId $identifier = null): self
    {
        if (!$identifier) {
            $identifier = new CartSessionId(CartSession::class, Uuid::uuid4());
        }

        $session = new self($identifier);
        $session->recordThat(new CartSessionInitiated($identifier));

        return $session;
    }

    public function add(Product $product, int $quantity): void
    {
        $this->recordThat(new ProductAdded(
            $this->identifier,
            $product->getSku(),
            $product->getUnitPrice(),
            $quantity
        ));
    }

    public function remove(): void
    {

    }

    public function save(): void
    {

    }

    public function empty(): void
    {
        $this->recordThat(new CartSessionEmptied($this->identifier));
    }

    public function checkOut(): void
    {

    }

    public function getIdentifier(): AggregateIdentifier
    {
        return $this->identifier;
    }

    public function getRecordedEvents(): RecordedEvents
    {
        return $this->recordedEvents;
    }
}