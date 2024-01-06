<?php

declare(strict_types=1);


namespace Test\Unit;

use App\Model\Basket;
use App\Model\Product;
use App\Model\Offer\Offer;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Exception;

class BasketTest extends TestCase
{
  public function testShouldAddProduct(): void
  {
    $testProduct = new Product('TEST-01', 'Video', 20000);
    $basket = new Basket();
    $basket->addProduct($testProduct);

    $products = $basket->getProducts();

    $this->assertCount(1, $products);
    $this->assertEquals($testProduct, $products[0]);
  }

  public function testShouldNotAddDuplicateProduct(): void
  {
    $testProduct = new Product('TEST-01', 'Video', 20000);
    $basket = new Basket();
    $basket->addProduct($testProduct);


    $this->expectException(Exception::class);
    $basket->addProduct($testProduct);
  }

  public function testShouldAddOffersOnIntialization(): void
  {
    $offers = [
      $this->createMock(Offer::class),
      $this->createMock(Offer::class),
    ];

    $basket = new Basket($offers);

    $this->assertCount(2, $basket->getOffers());
    $this->assertEquals($offers, $basket->getOffers());
  }

  public function testShouldGetProductsTotal(): void
  {
    $testProduct1 = new Product('TEST-01', 'Video', 20000);
    $testProduct2 = new Product('TEST-02', 'Photography', 10000);

    $basket = (new Basket())->addProduct($testProduct1)->addProduct($testProduct2);

    $this->assertEquals(30000, $basket->productsTotal());
  }

  public function testShouldGetZeroProductsTotal(): void
  {
    $basket = new Basket();

    $this->assertEquals(0, $basket->productsTotal());
  }


  /**
   * @dataProvider provideOffers
   */
  public function testShouldGetBasketTotalWithOffer(int ...$adjustments): void
  {
    $testProduct1 = new Product('TEST-01', 'Video', 20000);
    $testProduct2 = new Product('TEST-02', 'Photography', 10000);

    $offers = [];
    foreach ($adjustments as $adjustment) {
      /** @var Offer&MockObject $offer */
      $offer = $this->createMock(Offer::class);
      $offer->method('getAdjustment')->willReturn($adjustment);
      $offers[] = $offer;
    }

    $basket = (new Basket($offers))->addProduct($testProduct1)->addProduct($testProduct2);

    $adjustmentTotal = array_reduce($adjustments, fn (int $carry, int $adjustment) => $carry + $adjustment, 0);
    $expectedTotal = number_format((30000 - $adjustmentTotal) / 100, 2);

    $this->assertEquals($expectedTotal, $basket->basketTotal());
  }

  /**
   * @return array<string, int[]>
   */
  public static function provideOffers(): array
  {
    return [
      'No offers' => [],
      'One offer' => [
        5000
      ],
      'Two offers' => [
        5000,
        3000,
      ],
      'Three offers' => [
        5000,
        3000,
        7000,
      ]
    ];
  }
}
