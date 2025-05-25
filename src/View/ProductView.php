<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\View;

use Raketa\BackendTestTask\Domain\Product;

readonly class ProductView
{
    public function toArray(Product $product): array
    {
        return [
            'id'          => $product->getId(),
            'uuid'        => $product->getUuid(),
            'name'        => $product->getName(),
            'description' => $product->getDescription(),
            'category'    => $product->getCategory(),
            'thumbnail'   => $product->getThumbnail(),
            'price'       => $product->getPrice(),
            'is_active'   => $product->isActive(),
        ];
    }
}