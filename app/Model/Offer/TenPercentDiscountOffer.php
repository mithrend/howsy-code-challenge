<?php

declare(strict_types=1);

namespace App\Model\Offer;

use App\Model\Basket;

class TenPercentDiscountOffer implements Offer
{
    public function getAdjustment(Basket $basket): int
    {
        return (int)(round($basket->productsTotal() * 0.1));
    }
}
