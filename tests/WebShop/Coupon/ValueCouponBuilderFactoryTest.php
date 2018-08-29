<?php

namespace WebSummerCamp\Tests\WebShop\Coupon;

use Money\Currency;
use Money\Money;
use PHPUnit\Framework\TestCase;
use WebSummerCamp\WebShop\Coupon\ValueCoupon;
use WebSummerCamp\WebShop\Coupon\ValueCouponBuilder;
use WebSummerCamp\WebShop\Coupon\ValueCouponBuilderFactory;
use WebSummerCamp\WebShop\MoneyParser;

class ValueCouponBuilderFactoryTest extends TestCase
{
    public function testCreateCouponValueBuilder(): void
    {
        $factory = new ValueCouponBuilderFactory(new MoneyParser());

        $this->assertEquals(
            new ValueCouponBuilder(
                new ValueCoupon(
                    'CODE123',
                    new Money(1000, new Currency('EUR'))
                )
            ),
            $factory->createBuilder(['code' => 'CODE123', 'amount' => 'EUR 1000'])
        );
    }
}