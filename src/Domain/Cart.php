<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Domain;

use InvalidArgumentException;

final class Cart
{
    /**
     * @param CartItem[] $items
     */
    public function __construct(
        private readonly string $sessionId,
        private array           $items = [],
    )
    {
    }

    public function getSessionId(): string
    {
        return $this->sessionId;
    }

    /**
     * @return CartItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function addItem(CartItem $item): void
    {
        // Проверяем, есть ли уже такой товар
        foreach ($this->items as $key => $existingItem) {
            if ($existingItem->getProductUuid() === $item->getProductUuid()) {
                // Обновляем существующий товар с новым количеством
                $this->items[$key] = $existingItem->withQuantity(
                    $existingItem->getQuantity() + $item->getQuantity()
                );
                return;
            }
        }

        $this->items[] = $item;
    }

    public function updateItem(CartItem $item): void
    {
        foreach ($this->items as $key => $existingItem) {
            if ($existingItem->getUuid() === $item->getUuid()) {
                $this->items[$key] = $item;
                return;
            }
        }

        throw new InvalidArgumentException('Item not found in cart');
    }

    public function removeItem(string $itemUuid): void
    {
        foreach ($this->items as $key => $item) {
            if ($item->getUuid() === $itemUuid) {
                unset($this->items[$key]);
                $this->items = array_values($this->items);
                return;
            }
        }

        throw new InvalidArgumentException('Item not found in cart');
    }

    public function getTotalPrice(): float
    {
        return array_reduce(
            $this->items,
            fn(float $total, CartItem $item): float => $total + $item->getTotalPrice(),
            0.0
        );
    }

    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    public function clear(): void
    {
        $this->items = [];
    }

    public function getItemsCount(): int
    {
        return count($this->items);
    }

    public function hasProduct(string $productUuid): bool
    {
        foreach ($this->items as $item) {
            if ($item->getProductUuid() === $productUuid) {
                return true;
            }
        }

        return false;
    }

    public function findItem(string $itemUuid): ?CartItem
    {
        foreach ($this->items as $item) {
            if ($item->getUuid() === $itemUuid) {
                return $item;
            }
        }

        return null;
    }
}
