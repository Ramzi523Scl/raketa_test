<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Domain\Repository;

use Raketa\BackendTestTask\Domain\Product;

interface ProductRepositoryInterface
{
    public function findByUuid(string $uuid): ?Product;

    /**
     * @return Product[]
     */
    public function findByCategory(string $categoryId): array;
}