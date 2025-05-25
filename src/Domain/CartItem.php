<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Domain;

use InvalidArgumentException;

final readonly class CartItem
{
    public function __construct(
        private string $uuid,
        private string $productUuid,
        private float  $price,
        private int    $quantity,
    )
    {
        $this->validateQuantity($quantity);
        $this->validatePrice($price);
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getProductUuid(): string
    {
        return $this->productUuid;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getTotalPrice(): float
    {
        return $this->price * $this->quantity;
    }

    public function withQuantity(int $quantity): self
    {
        return new self(
            $this->uuid,
            $this->productUuid,
            $this->price,
            $quantity
        );
    }

    private function validateQuantity(int $quantity): void
    {
        if ($quantity <= 0) {
            throw new InvalidArgumentException('Quantity must be greater than zero');
        }
    }

    private function validatePrice(float $price): void
    {
        if ($price < 0) {
            throw new InvalidArgumentException('Price cannot be negative');
        }
    }
}
