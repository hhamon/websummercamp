<?php

namespace WebSummerCamp\WebShop\Coupon;

interface CouponBuilder
{
    public function getCoupon(): Coupon;
}