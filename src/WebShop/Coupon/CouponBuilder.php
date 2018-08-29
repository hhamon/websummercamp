<?php

namespace WebSummerCamp\WebShop\Coupon;

use Assert\Assertion;
use Money\Currency;
use Money\Money;

class CouponBuilder
{
    private $coupon;

    public static function ofValue(string $code, string $value): self
    {
        return new static(new ValueCoupon($code, static::parseMoney($value)));
    }

    public static function ofRate(string $code, float $rate): self
    {
        return new static(new RateCoupon($code, $rate));
    }

    private static function parseMoney(string $value): Money
    {
        Assertion::regex($value, '/^[A-Z]{3} \d+$/');

        [$currencyCode, $amount] = explode(' ', $value);

        return new Money($amount, new Currency($currencyCode));
    }

    private function __construct(Coupon $coupon)
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