<?php

namespace WebSummerCamp\WebShop\Coupon;

use Money\Money;

interface Coupon
{
    public function getCode(): string;

    public function apply(Money $totalAmount): Money;
}