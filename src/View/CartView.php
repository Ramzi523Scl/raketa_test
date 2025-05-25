<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\View;

use Raketa\BackendTestTask\Domain\Cart;
use Raketa\BackendTestTask\Domain\CartItem;

readonly class CartView
{
    public function toArray(Cart $cart): array
    {
        return [
            'session_id'  => $cart->getSessionId(),
            'items'       => array_map(
                fn(CartItem $item) => $this->cartItemToArray($item),
                $cart->getItems()
            ),
            'total_price' => $cart->getTotalPrice(),
            'items_count' => $cart->getItemsCount(),
        ];
    }

    private function cartItemToArray(CartItem $item): array
    {
        return [
            'uuid'         => $item->getUuid(),
            'product_uuid' => $item->getProductUuid(),
            'price'        => $item->getPrice(),
            'quantity'     => $item->getQuantity(),
            'total_price'  => $item->getTotalPrice(),
        ];
    }
}
