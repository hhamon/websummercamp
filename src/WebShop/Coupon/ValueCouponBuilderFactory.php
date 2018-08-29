<?php

namespace WebSummerCamp\WebShop\Coupon;

use WebSummerCamp\WebShop\MoneyParser;

class ValueCouponBuilderFactory implements CouponBuilderFactory
{
    private $moneyParser;

    public function __construct(MoneyParser $moneyParser)
    {
        $this->moneyParser = $moneyParser;
    }

    public function createBuilder(array $context): CouponBuilder
    {
        return new ValueCouponBuilder(
            new ValueCoupon(
                $context['code'],
                $this->moneyParser->parse($context['amount'])
            )
        );
    }
}