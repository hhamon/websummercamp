<?php

namespace WebSummerCamp\WebShop\Coupon;

class ValueCouponBuilder implements CouponBuilder
{
    private $coupon;

    public function __construct(ValueCoupon $coupon)
    {
        $this->coupon = $coupon;
    }

    public function mustRequireMinimumPurchaseAmount(string $value): self
    {
        $this->coupon = new MinimumPurchaseAmountCoupon($this->coupon, static::parseMoney($value));

        return $this;
    }

    public function mustBeValidBetween(string $from, string $until): self
    {
        $this->coupon = new LimitedLifetimeCoupon(
            $this->coupon,
            new \DateTimeImmutable($from),
            new \DateTimeImmutable($until)
        );

        return $this;
    }

    public function getCoupon(): Coupon
    {
        return $this->coupon;
    }
}