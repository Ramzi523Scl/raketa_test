<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Raketa\BackendTestTask\Service\ProductService;
use Raketa\BackendTestTask\View\ProductView;

final readonly class ProductController extends AbstractController
{
    public function __construct(
        private ProductService $productService,
        private ProductView    $productView,
        LoggerInterface        $logger
    ) {
        parent::__construct($logger);
    }

    public function getByCategory(RequestInterface $request, string $categoryId): ResponseInterface
    {
        try {
            $products = $this->productService->getByCategory($categoryId);

            return $this->success([
                'products' => array_map(
                    fn($product) => $this->productView->toArray($product),
                    $products
                )
            ]);
        } catch (\Throwable $e) {
            $this->logger->error('Failed to get products by category', [
                'category_id' => $categoryId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return $this->error('Failed to get products');
        }
    }
}