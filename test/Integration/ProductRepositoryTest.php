<?php

declare(strict_types=1);

namespace Test\Integration;

use App\Model\Product;
use App\Repository\ProductRepository;
use PHPUnit\Framework\TestCase;
use Exception;

class ProductRepositoryTest extends TestCase
{
  public function testShouldThrowErrorWhenGettingNonExistentProduct(): void
  {
    $repository = ProductRepository::getInstance();
    $this->expectException(Exception::class);

    $repository->get('non-existent-product-code');
  }

  public function testShouldGetAllProducts(): void
  {
    $repository = ProductRepository::getInstance();
    $products = $repository->getAll();

    $this->assertIsArray($products);
    $this->assertCount(4, $products);
  }

  /**
   * @dataProvider provideGetProduct
   */
  public function testShouldGetProduct(string $code, Product $product): void
  {
    $repository = ProductRepository::getInstance();
    $this->assertEquals($product, $repository->get($code));
  }

  /**
   * @return array<string, array{0: string, 1: Product}>
   */
  public static function provideGetProduct(): array
  {
    return [
      'Photography' => [
        'P001',
        new Product('P001', 'Photography', 20000)
      ],
      'Floorplan' => [
        'P002',
        new Product('P002', 'Floorplan', 10000)
      ],
      'Gas Certificate' => [
        'P003',
        new Product('P003', 'Gas Certificate', 8350)
      ],
      'EICR Certificate' => [
        'P004',
        new Product('P004', 'EICR Certificate', 5100)
      ],
    ];
  }
}
