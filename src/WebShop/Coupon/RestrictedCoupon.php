<?php

namespace WebSummerCamp\WebShop\Coupon;

use Money\Money;

abstract class RestrictedCoupon implements Coupon
{
    protected $coupon;

    public function __construct(Coupon $coupon)
    {
        $this->coupon = $coupon;
    }

    public function getCode(): string
    {
        return $this->coupon->getCode();
    }
}