<?php

namespace WebSummerCamp\WebShop\Coupon;

use Money\Money;

class LimitedLifetimeCoupon extends RestrictedCoupon
{
    private $validFrom;
    private $validUntil;

    public function __construct(
        Coupon $coupon,
        \DateTimeImmutable $validFrom,
        \DateTimeImmutable $validUntil
    ) {
        parent::__construct($coupon);

        $this->validFrom = $validFrom;
        $this->validUntil = $validUntil;
    }

    public function apply(Money $totalAmount): Money
    {
        $now = new \DateTimeImmutable('now');

        if ($now < $this->validFrom || $now > $this->validUntil) {
            return $totalAmount;
        }

        return $this->coupon->apply($totalAmount);
    }
}