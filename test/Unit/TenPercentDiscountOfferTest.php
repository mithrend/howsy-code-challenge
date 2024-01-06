<?php

declare(strict_types=1);

namespace Test\Unit;

use App\Model\Basket;
use App\Model\Offer\TenPercentDiscountOffer;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/** @package Test\Unit */
class TenPercentDiscountOfferTest extends TestCase
{
  public function testShouldReturnAdjustment(): void
  {
    /** @var MockObject&Basket $basket */
    $basket = $this->createMock(Basket::class);
    $basket->method('productsTotal')->willReturn(10000);

    $offer = new TenPercentDiscountOffer();
    $this->assertEquals(1000, $offer->getAdjustment($basket));
  }

  public function testShouldReturnZeroAdjustment(): void
  {
    /** @var MockObject&Basket $basket */
    $basket = $this->createMock(Basket::class);
    $basket->method('productsTotal')->willReturn(0);

    $offer = new TenPercentDiscountOffer();
    $this->assertEquals(0, $offer->getAdjustment($basket));
  }

  public function testShouldReturnRoundedAdjustment(): void
  {
    /** @var MockObject&Basket $basket */
    $basket = $this->createMock(Basket::class);
    $basket->method('productsTotal')->willReturn(103, 105, 108);

    $offer = new TenPercentDiscountOffer();
    $this->assertEquals(10, $offer->getAdjustment($basket));
    $this->assertEquals(11, $offer->getAdjustment($basket));
    $this->assertEquals(11, $offer->getAdjustment($basket));
  }
}
