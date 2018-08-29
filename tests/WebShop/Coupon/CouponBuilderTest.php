<?php

namespace WebSummerCamp\Tests\WebShop\Coupon;

use Money\Currency;
use Money\Money;
use PHPUnit\Framework\TestCase;
use WebSummerCamp\WebShop\Coupon\CouponBuilder;
use WebSummerCamp\WebShop\Coupon\LimitedLifetimeCoupon;
use WebSummerCamp\WebShop\Coupon\MinimumPurchaseAmountCoupon;
use WebSummerCamp\WebShop\Coupon\RateCoupon;
use WebSummerCamp\WebShop\Coupon\ValueCoupon;

class CouponBuilderTest extends TestCase
{
    public function testCreateComplexValueCouponCombination(): void
    {
        $expected = new LimitedLifetimeCoupon(
            new MinimumPurchaseAmountCoupon(
                new ValueCoupon('COUPON123', new Money(1500, new Currency('EUR'))),
                new Money(7500, new Currency('EUR'))
            ),
            new \DateTimeImmutable('2018-01-01 00:00:00'),
            new \DateTimeImmutable('2018-12-31 23:59:59')
        );

        $coupon = CouponBuilder::ofValue('COUPON123', 'EUR 1500')
            ->mustRequireMinimumPurchaseAmount('EUR 7500')
            ->mustBeValidBetween('2018-01-01 00:00:00', '2018-12-31 23:59:59')
            ->getCoupon()
        ;

        $this->assertEquals($expected, $coupon);
    }

    public function testCreateSimpleValueCoupon(): void
    {
        $this->assertEquals(
            new ValueCoupon('COUPON123', new Money(2000, new Currency('EUR'))),
            CouponBuilder::ofValue('COUPON123', 'EUR 2000')->getCoupon()
        );
    }

    public function testCreateSimpleRateCoupon(): void
    {
        $this->assertEquals(
            new RateCoupon('COUPON123', .25),
            CouponBuilder::ofRate('COUPON123', .25)->getCoupon()
        );
    }
}