<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Product;
use Exception;

class ProductRepository
{
  /** @var array<string,Product> $products */
  private array $products = [];

  private static ?ProductRepository $instance = null;

  private function __construct()
  {
    $this->buildProductCache();
  }

  /**
   * @throws Exception
   */
  private function buildProductCache(): void
  {
    $productsDataFile = __DIR__ . '/../../data/products.json';
    if (!file_exists($productsDataFile)) {
      throw new Exception('Data file not found');
    }

    $fileContents = file_get_contents($productsDataFile);

    if ($fileContents === false) {
      throw new Exception('Failed to read data file: ' . $productsDataFile);
    }

    $data = json_decode($fileContents, true);

    if (!is_array($data)) {
      throw new Exception('Invalid data');
    }

    foreach ($data as $productData) {
      $this->products[(string) $productData['code']] = new Product(
        $productData['code'],
        $productData['name'],
        $productData['price']
      );
    }
  }

  public static function getInstance(): ProductRepository
  {
    if (self::$instance === null) {
      self::$instance = new self();
    }

    return self::$instance;
  }

  /**
   * @throws Exception
   */
  public function get(string $code): Product
  {
    if (!isset($this->products[$code])) {
      throw new Exception('Product not found: ' . $code);
    }

    return $this->products[$code];
  }

  /**
   * @return Product[]
   */
  public function getAll(): array
  {
    return $this->products;
  }
}
