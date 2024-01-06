<?php

declare(strict_types=1);

namespace App\Model\Offer;

use App\Model\Basket;

interface Offer
{
    public function getAdjustment(Basket $basket): int;
}
