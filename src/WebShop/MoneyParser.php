<?php

namespace WebSummerCamp\WebShop;

use Assert\Assertion;
use Money\Currency;
use Money\Money;

class MoneyParser
{
    public function parse(string $value): Money
    {
        Assertion::regex($value, '/^[A-Z]{3} \d+$/');

        [$currencyCode, $amount] = explode(' ', $value);

        return new Money($amount, new Currency($currencyCode));
    }
}