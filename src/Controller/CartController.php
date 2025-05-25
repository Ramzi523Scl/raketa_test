<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Raketa\BackendTestTask\Service\CartService;
use Raketa\BackendTestTask\View\CartView;

final readonly class CartController extends AbstractController
{
    public function __construct(
        private CartService $cartService,
        private CartView    $cartView,
        LoggerInterface              $logger
    )
    {
        parent::__construct($logger);
    }

    public function add(RequestInterface $request): ResponseInterface
    {
        try {
            $rawRequest = json_decode($request->getBody()->getContents(), true);

            $cart = $this->cartService->addToCart(
                session_id(),
                $rawRequest['productUuid'],
                (int)$rawRequest['quantity']
            );

            return $this->success([
                'cart' => $this->cartView->toArray($cart),
            ]);
        } catch (\Throwable $e) {
            $this->logger->error('Failed to add item to cart', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return $this->error('Failed to add item to cart');
        }
    }

    public function get(RequestInterface $request): ResponseInterface
    {
        try {
            $cart = $this->cartService->getCart(session_id());

            return $this->success([
                'cart' => $this->cartView->toArray($cart),
            ]);
        } catch (\Throwable $e) {
            $this->logger->error('Failed to get cart', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return $this->error('Failed to get cart');
        }
    }
}