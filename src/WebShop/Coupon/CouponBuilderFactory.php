<?php

namespace WebSummerCamp\WebShop\Coupon;

interface CouponBuilderFactory
{
    public function createBuilder(array $context): CouponBuilder;
}