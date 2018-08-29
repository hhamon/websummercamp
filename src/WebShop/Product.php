<?php

namespace WebSummerCamp\WebShop;

use Money\Money;
use Ramsey\Uuid\UuidInterface;

interface Product
{
    public function getSku(): UuidInterface;

    public function getName(): string;

    public function getUnitPrice(): Money;

    //public function getWeight(): Weight;
}