<?php

declare(strict_types=1);

namespace App\Model;

use Exception;
use App\Model\Offer\Offer;


class Basket
{
    /** @var Product[] $products */
    private array $products = [];

    /** @param Offer[] $offers */
    public function __construct(
        private readonly array $offers  = [],
    ) {
    }

    /**
     * @throws Exception
     */
    public function addProduct(Product $product): self
    {
        if ($this->productIsInBasket($product)) {
            throw new Exception('Product already in basket, product code: ' . $product->code);
        }

        $this->products[] = $product;

        return $this;
    }

    /**
     * @return Product[]
     */
    function getProducts(): array
    {
        return $this->products;
    }

    /**
     * @return Offer[] $offers
     */
    function getOffers(): array
    {
        return $this->offers;
    }

    private function productIsInBasket(Product $product): bool
    {
        foreach ($this->products as $basketProduct) {
            if ($basketProduct->code === $product->code) {
                return true;
            }
        }

        return false;
    }

    public function productsTotal(): int
    {
        return array_reduce(
            $this->products,
            static fn (int $carry, Product $product) => $carry + $product->price,
            0
        );
    }

    public function basketTotal(): string
    {
        $totalOfferAdjustment = array_reduce(
            $this->offers,
            fn (int $carry, Offer $offer) => $carry + $offer->getAdjustment($this),
            0
        );

        $totalInMinorDenomination = $this->productsTotal() - $totalOfferAdjustment;

        return number_format($totalInMinorDenomination / 100, 2);
    }
}
