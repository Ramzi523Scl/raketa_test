<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Domain\Repository;

use Raketa\BackendTestTask\Domain\Cart;

interface CartRepositoryInterface
{
    public function find(string $sessionId): ?Cart;

    public function save(Cart $cart): void;

    /**
     * Удаляет корзину по идентификатору сессии
     */
    public function delete(string $sessionId): void;
}