<?php

declare(strict_types=1);

namespace Test\Integration;

use App\Model\Basket;
use App\Model\Offer\TenPercentDiscountOffer;
use App\Repository\ProductRepository;
use PHPUnit\Framework\TestCase;

class CheckoutTest extends TestCase
{
  /**
   * This test covers the happy path using all the parts of the application
   * and can be used as an example of how the pieces fit together.
   */
  public function testShouldGetBasketTotal(): void
  {
    $offer = new TenPercentDiscountOffer();

    $repository = ProductRepository::getInstance();
    $product1 = $repository->get('P001');
    $product2 = $repository->get('P002');

    $basket = new Basket([$offer]);
    $basket->addProduct($product1)->addProduct($product2);

    $this->assertEquals('270.00', $basket->basketTotal());
  }
}
