<?php

namespace WebSummerCamp\WebShop;

use Money\Money;
use Ramsey\Uuid\UuidInterface;

class PhysicalProduct implements Product
{
    private $sku;
    private $unitPrice;
    private $name;

    public function __construct(
        UuidInterface $sku,
        Money $unitPrice,
        string $name
    ) {
        $this->sku = $sku;
        $this->name = $name;
        $this->unitPrice = $unitPrice;
    }

    public function getSku(): UuidInterface
    {
        return $this->sku;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUnitPrice(): Money
    {
        return $this->unitPrice;
    }
}