<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Service;

use InvalidArgumentException;
use Raketa\BackendTestTask\Domain\Cart;
use Raketa\BackendTestTask\Domain\CartItem;
use Raketa\BackendTestTask\Domain\Repository\CartRepositoryInterface;
use Ramsey\Uuid\Uuid;

readonly class CartService
{
    public function __construct(
        private CartRepositoryInterface $cartRepository,
        private ProductService          $productService
    )
    {
    }

    public function getCart(string $sessionId): Cart
    {
        $cart = $this->cartRepository->find($sessionId);

        if ($cart === null) {
            $cart = new Cart($sessionId);
        }

        return $cart;
    }

    public function addToCart(string $sessionId, string $productUuid, int $quantity): Cart
    {
        $product = $this->productService->getByUuid($productUuid);

        if ($product === null) {
            throw new InvalidArgumentException('Product not found');
        }

        $cart = $this->getCart($sessionId);

        $cart->addItem(new CartItem(
            Uuid::uuid4()->toString(),
            $productUuid,
            $product->getPrice(),
            $quantity
        ));

        $this->cartRepository->save($cart);

        return $cart;
    }
}