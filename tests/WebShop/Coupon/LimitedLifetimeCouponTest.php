<?php

namespace WebSummerCamp\Tests\WebShop\Coupon;

use Money\Currency;
use Money\Money;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\PhpUnit\ClockMock;
use WebSummerCamp\WebShop\Coupon\LimitedLifetimeCoupon;
use WebSummerCamp\WebShop\Coupon\MinimumPurchaseAmountCoupon;
use WebSummerCamp\WebShop\Coupon\RateCoupon;
use WebSummerCamp\WebShop\Coupon\ValueCoupon;

class LimitedLifetimeCouponTest extends TestCase
{
    public function testComplexCouponCombination(): void
    {
        ClockMock::withClockMock('2018-06-11 10:30:30');

        $coupon = new LimitedLifetimeCoupon(
            new MinimumPurchaseAmountCoupon(
                new RateCoupon('COUPON123', .20),
                new Money(7500, new Currency('EUR'))
            ),
            new \DateTimeImmutable('2018-01-01 00:00:00'),
            new \DateTimeImmutable('2018-12-31 23:59:59')
        );

        $this->assertEquals(
            new Money(8000, new Currency('EUR')),
            $coupon->apply(new Money(10000, new Currency('EUR')))
        );
    }

    public function testCouponIsEligible(): void
    {
        ClockMock::withClockMock('2018-06-11 10:30:30');

        $coupon = new LimitedLifetimeCoupon(
            new ValueCoupon('COUPON123', new Money(2000, new Currency('EUR'))),
            new \DateTimeImmutable('2018-01-01 00:00:00'),
            new \DateTimeImmutable('2018-12-31 23:59:59')
        );

        $this->assertEquals(
            new Money(9000, new Currency('EUR')),
            $coupon->apply(new Money(11000, new Currency('EUR')))
        );
    }

    public function testCouponIsNotEligible(): void
    {
        ClockMock::withClockMock('2018-06-11 10:30:30');

        $coupon = new LimitedLifetimeCoupon(
            new ValueCoupon('COUPON123', new Money(2000, new Currency('EUR'))),
            new \DateTimeImmutable('2018-01-01 00:00:00'),
            new \DateTimeImmutable('2018-05-31 23:59:59')
        );

        $this->assertEquals(
            new Money(11000, new Currency('EUR')),
            $coupon->apply(new Money(11000, new Currency('EUR')))
        );
    }
}