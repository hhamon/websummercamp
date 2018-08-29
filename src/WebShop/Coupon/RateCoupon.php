<?php

namespace WebSummerCamp\WebShop\Coupon;

use Assert\Assertion;
use Money\Money;

class RateCoupon implements Coupon
{
    private $code;
    private $discountRate;

    public function __construct(string $code, float $rate)
    {
        Assertion::between($rate, 0, 1);

        $this->code = $code;
        $this->discountRate = $rate;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function apply(Money $totalAmount): Money
    {
        return $totalAmount->subtract($totalAmount->multiply($this->discountRate));
    }
}