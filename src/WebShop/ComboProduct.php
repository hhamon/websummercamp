<?php

namespace WebSummerCamp\WebShop;

use Assert\Assertion;
use Money\Money;
use Ramsey\Uuid\UuidInterface;

class ComboProduct implements Product
{
    private $sku;
    private $unitPrice;
    private $name;
    private $products;

    public function __construct(
        UuidInterface $sku,
        string $name,
        array $products,
        Money $unitPrice = null
    ) {
        Assertion::allIsInstanceOf($products, Product::class);
        Assertion::min(count($products), 2);

        $this->sku = $sku;
        $this->name = $name;
        $this->unitPrice = $unitPrice;
        $this->products = array_values($products);
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
        if ($this->unitPrice) {
            return $this->unitPrice;
        }

        $totalPrice = $this->products[0]->getUnitPrice();
        $max = count($this->products);
        for ($i = 1; $i < $max; $i++) {
            $totalPrice = $totalPrice->add($this->products[$i]->getUnitPrice());
        }

        return $totalPrice;
    }
}