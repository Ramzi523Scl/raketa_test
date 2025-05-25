<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Service;

use Raketa\BackendTestTask\Domain\Product;
use Raketa\BackendTestTask\Domain\Repository\ProductRepositoryInterface;

readonly class ProductService
{
    public function __construct(
        private ProductRepositoryInterface $productRepository
    )
    {
    }

    /**
     * @return Product[]
     */
    public function getByCategory(string $categoryId): array
    {
        return $this->productRepository->findByCategory($categoryId);
    }

    public function getByUuid(string $uuid): ?Product
    {
        return $this->productRepository->findByUuid($uuid);
    }
}