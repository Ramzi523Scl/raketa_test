<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Infrastructure\Persistence\MySQL;

use Doctrine\DBAL\Connection;
use Psr\Log\LoggerInterface;
use Raketa\BackendTestTask\Domain\Product;
use Raketa\BackendTestTask\Domain\Repository\ProductRepositoryInterface;

readonly class DoctrineProductRepository implements ProductRepositoryInterface
{
    public function __construct(
        private Connection      $connection,
        private LoggerInterface $logger
    )
    {
    }

    public function findByUuid(string $uuid): ?Product
    {
        try {
            $row = $this->connection->fetchAssociative(
                'SELECT * FROM products WHERE uuid = :uuid',
                ['uuid' => $uuid]
            );

            if ($row === false) {
                return null;
            }

            return $this->make($row);
        } catch (\Throwable $e) {
            $this->logger->error('Failed to fetch product by UUID', [
                'uuid'  => $uuid,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    public function findByCategory(string $categoryId): array
    {
        try {
            $rows = $this->connection->fetchAllAssociative(
                'SELECT * FROM products WHERE is_active = :active AND category = :category',
                [
                    'active'   => true,
                    'category' => $categoryId,
                ]
            );

            return array_map(
                fn(array $row): Product => $this->make($row),
                $rows
            );
        } catch (\Throwable $e) {
            $this->logger->error('Failed to fetch products by category', [
                'category' => $categoryId,
                'error'    => $e->getMessage(),
                'trace'    => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    private function make(array $row): Product
    {
        return new Product(
            $row['id'],
            $row['uuid'],
            (bool)$row['is_active'],
            $row['category'],
            $row['name'],
            $row['description'],
            $row['thumbnail'],
            (float)$row['price']
        );
    }
}