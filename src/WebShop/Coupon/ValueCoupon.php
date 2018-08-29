<?php

namespace WebSummerCamp\WebShop\Coupon;

use Money\Money;

class ValueCoupon implements Coupon
{
    private $code;
    private $discount;

    public function __construct(string $code, Money $discount)
    {
        $this->code = $code;
        $this->discount = $discount;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function apply(Money $totalAmount): Money
    {
        if ($totalAmount->lessThan($this->discount)) {
            throw new \InvalidArgumentException('Total amount is lower than value coupon.');
        }

        return $totalAmount->subtract($this->discount);
    }
}