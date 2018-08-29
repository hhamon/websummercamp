<?php

namespace WebSummerCamp\WebShop\Coupon;

use Money\Money;

class MinimumPurchaseAmountCoupon extends RestrictedCoupon
{
    private $minimumRequiredAmount;

    public function __construct(Coupon $coupon, Money $minimumRequiredAmount)
    {
        parent::__construct($coupon);

        $this->minimumRequiredAmount = $minimumRequiredAmount;
    }

    public function apply(Money $totalAmount): Money
    {
        if ($totalAmount->lessThan($this->minimumRequiredAmount)) {
            return $totalAmount;
        }

        return $this->coupon->apply($totalAmount);
    }
}